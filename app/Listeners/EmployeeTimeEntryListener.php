<?php

namespace App\Listeners;

use App\Events\EmployeeTimeEntry;
use App\Models\EmployeeTimeLogEntry;
use App\Models\EmployeeTimeLogs;
use DateTime;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class EmployeeTimeEntryListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    // public function subscribe($events)
    // {
    //     $events->listen(
    //         'App\Events\EmployeeTimeEntry',
    //         'App\Listeners\EmployeeTimeEntryListener@onBookingCanceled'
    //     );
    // }

    /**
     * Handle the event.
     *
     * @param  EmployeeTimeEntry  $event
     * @return void
     */
    public function handle(EmployeeTimeEntry $event)
    {
       
        // $dateTime = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")));
        $dateTime = EmployeeTimeLogEntry::select('log_date')->latest()->first();
        $employeeDetails = EmployeeTimeLogEntry::where('log_date',$dateTime->log_date)
        ->select(\DB::raw("GROUP_CONCAT(log_time) as logtime"),'user_id','log_date')->groupBy('user_id')->get();
        $employeeLogs = [];
        foreach($employeeDetails as $key => $val)
        {
            $logArray = explode(',',$val->logtime);
            $userId = $val->user_id;
            $logDate = $val->log_date;
            $odd=array();
            $even=array();
            $count=1;

            foreach($logArray as $val)
            {
                if($count%2==1)
                {
                    $odd[]=$val;
                }
                else
                {
                    $even[]=$val;
                }
                $count++;
            }

            $mainArray = [];
            $oddsize = count($odd);
            $evensize = count($even);
            foreach($even as $k => $val)
            {
                $listArray = [];
                $listArray['inTime'] =  date('H:i:s', strtotime($odd[$k]));
                $listArray['outTime'] = date('H:i:s', strtotime($even[$k]));

                $datetime1 = new DateTime($listArray['inTime']);
                $datetime2 = new DateTime($listArray['outTime']);
                $interval = $datetime1->diff($datetime2);
                $listArray['duration'] =  $interval->format('%H').":".$interval->format('%I').":".$interval->format('%S');

                unset($odd[$k]);
                $employeeLogs[$key]['timeEntry'][] = $listArray;
            }

            if(!empty($odd))
            {
                foreach($odd as $oddval)
                {
                    $listArray = [];
                    $listArray['inTime'] =  date('H:i:s', strtotime($oddval));
                    $listArray['outTime'] = date('H:i:s', strtotime($oddval));
                    $datetime1 = new DateTime($listArray['inTime']);
                    $datetime2 = new DateTime($listArray['outTime']);
                    $interval = $datetime1->diff($datetime2);
                    $listArray['duration'] =  $interval->format('%H').":".$interval->format('%I').":".$interval->format('%S');

                    $employeeLogs[$key]['timeEntry'][] = $listArray;
                }
            }
            $minutes = 0;
            $seconds = 0;
            foreach ($employeeLogs[$key]['timeEntry'] as $val)
            {
                list($hour, $minute, $sec) = explode(':', $val['duration']);
                $minutes += $hour * 60;
                $minutes += $minute;
                $seconds += $sec;
                $minutes += $sec / 60 ;
            }
            
            $hours = floor($minutes / 60);
            $minutes -= $hours * 60;
            
            // returns the time already formatted
            $logtime = sprintf('%02d:%02d', $hours, $minutes);
            $employeeLogs[$key]['totalduration'] = $logtime;
            $employeeLogs[$key]['user_id'] = $userId;
            $employeeLogs[$key]['log_date'] = $logDate;
        }
        
        if(isset($employeeLogs) && !empty($employeeLogs)){
           
            foreach($employeeLogs as $k => $v)
            {
                $isExistEmployeeLog = EmployeeTimeLogs::where('user_id',$v['user_id'])->where('log_date',$v['log_date'])->get();
                
                if($isExistEmployeeLog->count() <= 0){
                    $employeeLogsDetails = new EmployeeTimeLogs();
                    $employeeLogsDetails->log_in_time = implode(',',array_column($v['timeEntry'],'inTime'));
                    $employeeLogsDetails->log_out_time = implode(',',array_column($v['timeEntry'],'outTime'));
                    $employeeLogsDetails->duration = implode(',',array_column($v['timeEntry'],'duration'));
                    $employeeLogsDetails->total_duration = $v['totalduration'];
                    $employeeLogsDetails->user_id = $v['user_id'];
                    $employeeLogsDetails->log_date = $v['log_date'];
                    $employeeLogsDetails->save();
                }
                
            }
           
        }
    }
}
