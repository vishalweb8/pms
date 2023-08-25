<?php

namespace App\Models;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeEntry extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'EmployeeCode',
        'LogDateTime',
        'LogTime',
        'Direction',
        'WorkCode',
    ];

    protected $appends = ['duration','total_duration'];

    public function officialDetail()
    {
        return $this->belongsTo(UserOfficialDetail::class, 'EmployeeCode','emp_code');
    }
    
    public function getDurationAttribute()
    {
        $durations = getDurations($this->log_out_time,$this->log_in_time);
        return $durations;
    }

    public function getTotalDurationAttribute()
    {
        $durations = getDurations($this->log_out_time,$this->log_in_time);
        $totalTime = Helper::calulateTotalDuration($durations);
        return $totalTime;
    }

    public function scopeDurations($query)
    {
        return $query->select('time_entries.id', 'EmployeeCode',
            \DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN Direction='in' THEN LogTime END ORDER BY LogTime ASC) as log_in_time"),
            \DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN Direction='out' THEN LogTime END ORDER BY LogTime ASC) as log_out_time"),
            \DB::raw("DATE(LogDateTime) AS log_date")
            )
            ->groupBy(['EmployeeCode','log_date']);

    }
}
