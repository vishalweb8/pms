<?php

namespace App\Models;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PDO;

class DailyTask extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'current_date',
        'sod_description',
        'eod_description',
        'emp_status',
        'project_status',
        'verified_by_TL',
        'verified_by_Admin',
    ];

    // public function setCurrentDateAttribute($value)
    // {
    //     $this->attributes['current_date'] = Helper::setDateFormat($value);
    // }

    public function getCurrentDateAttribute($value)
    {
        return Helper::getDateFormat($value);
    }

    public function userTask()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withBlocked();
    }

    public function userOfficial()
    {
        return $this->belongsTo(UserOfficialDetail::class, 'user_id','user_id');
    }

    public function projectTask()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function freeResource()
    {
        return $this->hasOne(FreeResource::class, 'id', 'daily_task_id');
    }
}
