<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeTimeLogEntry extends Model
{
    use HasFactory;

    protected $table = 'employee_time_log_entries';

    protected $fillable = [
        'user_id',
        'log_date',
        'log_time',
        'status'
    ];

    protected $date = ['log_date'];

    public function userTimeEntry()
    {
        return $this->belongsTo(User::class, 'user_id')->withBlocked();
    }
}
