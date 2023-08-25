<?php

namespace App\Imports;

use App\Models\EmployeeTimeLogEntry;
use Maatwebsite\Excel\Concerns\ToModel;

class Importempentrytime implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new EmployeeTimeLogEntry([
            'user_id' => $row[0],
            'log_date' => \Carbon\carbon::now()->format('Y-m-d'),
            'log_time' => $row[2],
            'status' => $row[3]
        ]);
    }
}
