<?php

namespace App\Http\Controllers\TimeLogEntry;

use App\DataTables\TimeEntryDataTable;
use App\Events\EmployeeTimeEntry;
use App\Http\Controllers\commonController;
use App\Http\Controllers\Controller;
use App\Imports\EmployeeLogEntryImport;
use DateTime;
use Illuminate\Http\Request;
use Excel;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;
use Spatie\Permission\Exceptions\UnauthorizedException;

class EmpTimeLogEntryController extends Controller
{
    public function index(TimeEntryDataTable $dataTable)
    {
        $empCode = Auth::user()->officialUser->emp_code ?? null;
        $lastDay = Helper::lastDay($empCode);
        $thisWeek = Helper::thisWeek($empCode);
        $aveMonth = Helper::currentMonth($empCode);

        return $dataTable->render('time-entry.myTimeEntry',compact('lastDay','thisWeek','aveMonth'));
    }

    public function allEmpTimeEntry(TimeEntryDataTable $dataTable)
    {

        if( ! Helper::hasAnyPermission('all-emp-time-entry.list')) {
            $exception = new UnauthorizedException(403, trans('messages.PERMISSION_ERROR'));
            return view('errors.403')->with(['exception'=>$exception]);
        }
        $today = today()->format('d-m-Y');
        $teams = (new commonController)->getTeams();
        return $dataTable->render('time-entry.allEmployeeTimeEntry',compact('teams','today'));
    }

    public function importTimeLog(Request $request)
    {
        try {
            Excel::import(new EmployeeLogEntryImport,request()->file('file_url'));
            event(new EmployeeTimeEntry());
            // return Redirect::to("admin/employee")->with('success', trans('labels.employeelogaddsuccessmsg'));
            return response()->json(['status' => 200,'message' => trans('labels.employeelogaddsuccessmsg')], 200);

        } catch (Exception $e) {
        }
    }

    public static function loglist($employeeLogs)
    {
        foreach ($employeeLogs as $key => $log) {
            $logArray = explode(',', $log['ele_log_time']);
            $odd = array();
            $even = array();
            $count = 1;
            foreach ($logArray as $val) {
                if ($count % 2 == 1) {
                    $odd[] = $val;
                } else {
                    $even[] = $val;
                }
                $count++;
            }

            $mainArray = [];
            $oddsize = count($odd);
            $evensize = count($even);
            foreach ($even as $k => $val) {
                $listArray = [];
                $listArray['inTime'] =  date('H:i', strtotime($odd[$k]));
                $listArray['outTime'] = date('H:i', strtotime($even[$k]));

                $datetime1 = new DateTime($listArray['inTime']);
                $datetime2 = new DateTime($listArray['outTime']);
                $interval = $datetime1->diff($datetime2);
                $listArray['duration'] =  $interval->format('%H') . ":" . $interval->format('%I');

                unset($odd[$k]);
                $employeeLogs[$key]['timeEntry'][] = $listArray;
            }

            if (!empty($odd)) {
                foreach ($odd as $oddval) {
                    $listArray = [];
                    $listArray['inTime'] =  date('H:i', strtotime($oddval));
                    $listArray['outTime'] = date('H:i', strtotime($oddval));
                    $datetime1 = new DateTime($listArray['inTime']);
                    $datetime2 = new DateTime($listArray['outTime']);
                    $interval = $datetime1->diff($datetime2);
                    $listArray['duration'] =  $interval->format('%H') . ":" . $interval->format('%I');

                    $employeeLogs[$key]['timeEntry'][] = $listArray;
                }
            }
            $minutes = 0;
            foreach ($employeeLogs[$key]['timeEntry'] as $val) {
                list($hour, $minute) = explode(':', $val['duration']);
                $minutes += $hour * 60;
                $minutes += $minute;
            }

            $hours = floor($minutes / 60);
            $minutes -= $hours * 60;

            // returns the time already formatted
            $logtime = sprintf('%02d:%02d', $hours, $minutes);
            $employeeLogs[$key]['totalduration'] = $logtime;
        }

        return $employeeLogs;
    }
}
