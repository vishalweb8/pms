<?php

namespace App\Imports;

use App\Events\EmployeeTimeEntry;
use App\Helpers\Helper;
use App\Models\EmployeeTimeLogEntry;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeLogEntryImport implements ToModel , WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $dateTime = strtotime($row['log_date']);
        $row['log_date'] = date('Y-m-d', $dateTime);
        $row['log_time'] = date('H:i:s', $dateTime);
        $row['user_id'] = Helper::getEmployeeIdByCode($row['employee_code'],'emp_code','user_id');
        $isExistEmployeeRecord = EmployeeTimeLogEntry::
            where('user_id',$row['user_id'])
        ->where('log_date',$row['log_date'])
        ->where('log_time',$row['log_time'])
        ->get();
        if($isExistEmployeeRecord->count() <= 0){
            return new EmployeeTimeLogEntry([
                'user_id'     => $row['user_id'],
                'log_date'    => $row['log_date'],
                'log_time'    => $row['log_time'],
                'status'    => 1,
            ]);
        }
        else{
            return null;
        }
    }
}
