<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeTimeLogs extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'log_date',
        'log_in_time',
        'log_out_time',
        'duration',
        'total_duration'
    ];
    public function userTimeEntry()
    {
        return $this->belongsTo(User::class, 'user_id')->withBlocked();
    }
}
