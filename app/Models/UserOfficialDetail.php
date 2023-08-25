<?php

namespace App\Models;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserOfficialDetail extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id',
        'team_leader_id',
        'emp_code',
        'joining_date',
        'confirmation_date',
        'resigned_date',
        'offered_ctc',
        'current_ctc',
        'experience',
        'team_id',
        'designation_id',
        'department_id',
        'skype_id',
        'company_email_id',
        'company_gmail_id',
        'company_gitlab_id',
        'company_github_id',
        'technologies_ids',
        'reporting_ids',
        'task_entry_date'
    ];

    protected $dates = [ 'deleted_at' ];

    public function setJoiningDateAttribute($value)
    {
        $this->attributes['joining_date'] = Helper::setDateFormat($value);
    }

    public function setConfirmationDateAttribute($value)
    {
        $this->attributes['confirmation_date'] = Helper::setDateFormat($value);
    }

    public function setTaskEntryDateAttribute($value)
    {
        $this->attributes['task_entry_date'] = Helper::setDateFormat($value);
    }

    public function getJoiningDateAttribute($value)
    {
        return Helper::getDateFormat($value);
    }

    public function getConfirmationDateAttribute($value)
    {
        return Helper::getDateFormat($value);
    }

    public function getTaskEntryDateAttribute($value)
    {
        return Helper::getDateFormat($value);
    }

    public function userOfficial()
    {
        return $this->belongsTo(User::class, 'user_id')->withBlocked();
    }
    public function activeUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function userTeam()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function userDesignation()
    {
        return $this->belongsTo(UserDesignation::class, 'designation_id');
    }

    public function userTeamLeader()
    {
        return $this->belongsTo(User::class, 'team_leader_id')->withBlocked();
    }

    public function userDepartment()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function teamsLeave()
    {
        return $this->belongsTo(Team::class, 'team_id','id');
    }

    public function timeEntry()
    {
        return $this->hasMany(TimeEntry::class, 'EmployeeCode','emp_code')->durations();
    }

}
