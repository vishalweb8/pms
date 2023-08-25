<?php

namespace App\Models;

use App\Helpers\Helper;
use App\Http\Traits\ActiveTrait;
use App\Notifications\PasswordReset;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles, ActiveTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'role_id',
        'user_name',
        'profile_image',
        'email',
        'personal_email',
        'password',
        'phone_number',
        'emergency_number',
        'temp_address1',
        'temp_address2',
        'temp_zipcode',
        'temp_contry',
        'temp_state',
        'temp_city',
        'address1',
        'address2',
        'city',
        'state',
        'contry',
        'zipcode',
        'full_name',
        'last_login_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = [ 'deleted_at', 'wedding_anniversary' ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['full_name', 'full_address', 'profile_picture', 'is_blocked', 'experience'];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordReset($token));
    }

    public function getFullNameAttribute()
    {
        $full_name =  $this->first_name;
        if(!empty($this->last_name)) {

            $full_name .=  ' ' . $this->last_name;
        }
        return $full_name;
    }

    public function getIsBlockedAttribute()
    {
        return !$this->status;
    }

    public function setBirthDateAttribute($value)
    {
        $this->attributes['birth_date'] = Helper::setDateFormat($value);
    }

    public function setWeddingAnniversaryAttribute($value)
    {
        $this->attributes['wedding_anniversary'] = Helper::setDateFormat($value);
    }

    public function getBirthDateAttribute($value)
    {
        return Helper::getDateFormat($value);
    }

    public function getWeddingAnniversaryAttribute($value)
    {
        return Helper::getDateFormat($value);
    }

    public function temp_cities()
    {
        return $this->belongsTo("App\Models\City",'temp_city');
    }
    public function temp_states()
    {
        return $this->belongsTo("App\Models\State",'temp_state');
    }
    public function temp_country()
    {
        return $this->belongsTo("App\Models\Country",'temp_contry');
    }

    public function cities()
    {
        return $this->belongsTo("App\Models\City",'city');
    }
    public function states()
    {
        return $this->belongsTo("App\Models\State",'state');
    }
    public function country()
    {
        return $this->belongsTo("App\Models\Country",'contry');
    }

    public function teamLeaders()
    {
        return $this->belongsToMany(User::class,'team_leader_members','member_id','user_id');
    }

    public function teamLeaderMembers()
    {
        return $this->belongsToMany(User::class,'team_leader_members','user_id','member_id');
    }

    public function resourceProjects()
    {
        return $this->hasMany(ResourceManagement::class,'user_id')->orderBy('status')->with('project')->has('project');
    }

    public function getTempFullAddressAttribute()
    {
        $country = ($this->temp_contry) ? $this->temp_country->name : '';
        $state = ($this->temp_state) ? $this->temp_states->name : '';
        $city = ($this->temp_city) ? $this->temp_cities->name : '';
        $full_address  = implode(", ", array_filter([$this->temp_address1, $city, $state, $country]));
        return $full_address;
    }

    public function getFullAddressAttribute()
    {
        $country = ($this->contry) ? $this->country->name : '';
        $state = ($this->state) ? $this->states->name : '';
        $city = ($this->city) ? $this->cities->name : '';
        $full_address  = implode(", ",array_filter([$this->address1,$this->address2,$city,$state,$country,$this->zipcode]));
        return $full_address;
    }

    public function userRole()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function officialUser()
    {
        return $this->belongsTo(UserOfficialDetail::class, 'id', 'user_id');
    }

    public function userTeam()
    {
        return $this->belongsTo(Team::class, 'id', 'team_lead_id');
    }

    public function userEducation()
    {
        return $this->hasMany(UserEducation::class, 'user_id');
    }

    public function userExperience()
    {
        return $this->hasMany(UserExperience::class, 'user_id');
    }

    public function userBank()
    {
        return $this->hasOne(UserBank::class, 'user_id');
    }

    public function userFamily()
    {
        return $this->hasMany(UserFamily::class,  'user_id');
    }

    public function userDailyTask()
    {
        return $this->hasMany(DailyTask::class, 'user_id', 'id');
    }

    public function userProjects()
    {
        return $this->hasMany(UserProjects::class, 'user_id', 'id')->with('project');
    }

    public function bdeProjects()
    {
        return $this->hasMany(Project::class, 'bde_id', 'id')->with('projectStatus');
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class, 'request_from', 'id')
                    ->where('status', '<>', 'cancelled');
    }

    public function approvedLeaves()
    {
        $fYear = $this->getFinancialYear();
        return $this->hasMany(Leave::class, 'request_from', 'id')
                    ->where('status', '=', 'approved')
                    ->whereYear('start_date',$fYear);
    }

    //there will be multiple leave allocation i.e hasMany but for 1 FY it will be only 1 hence hasOne
    public function leaveAllocationByFY()
    {
        return $this->hasOne(LeaveAllocation::class, 'user_id', 'id')->where('allocated_year', Helper::getFinancialStartYearFromDate(today()));
    }

    public function allLeaves()
    {
        return $this->hasMany(Leave::class, 'request_from', 'id');
    }

    public function allocatedLeaves()
    {
        $fYear = request('fyear');
        if(!empty($fYear)) {
            $financialYear = explode('-',$fYear);
            $fYear = $financialYear[0] ?? currentFinStartYear();
        } else {
            $fYear = currentFinStartYear();
        }

        return $this->hasOne(LeaveAllocation::class)->where('allocated_year',$fYear);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class, 'lead_owner_id', 'id');
    }

    public function freeResource()
    {
        return $this->hasMany(FreeResource::class, 'user_id', 'id');
    }

    public function workFromHome()
    {
        return $this->hasMany(WorkFromHome::class, 'user_id', 'id');
    }

    public function tasks()
    {
        return $this->hasMany(ProjectTaskWorkLog::class, 'user_id', 'id')->has('logProjectTask');
    }

    public function workLogData()
    {
        return $this->hasMany(ProjectTaskWorkLog::class, 'user_id', 'id')->has('logProjectTask');
    }

    public function approvedLeaveCompensation()
    {
        $fYear = $this->getFinancialYear();
        return $this->hasMany(LeaveCompensation::class, 'request_from', 'id')
                    ->where('status', '=', 'approved')
                    ->whereYear('start_date',$fYear);
    }
    public function getProfilePictureAttribute()
    {
        $first_name = $this->first_name;
        $last_name = $this->last_name;
        if (!isset($last_name)) {
            $initials = $first_name[0] . $first_name[1];
        } else {
            $initials = $first_name[0] . $last_name[0];
        }

        return strtoupper($initials);
    }

    public function getUserDetailById($userId)
    {

        return User::find($userId);
    }

    /**
     * for check login user is management team or not
     *
     * @return void
     */
    public function isManagement()
    {
        $code = Auth::user()->userRole->code ?? null;
        return in_array($code, ['ADMIN', 'PM', 'HRA']);
    }
    public function getFinancialYear()
    {
        $fYear = currentFinStartYear();
        if(request()->filled('fyear')) {
            $financialYear = explode('-',request()->fyear);
            $fYear = $financialYear[0] ?? currentFinStartYear();
        }
        return $fYear;
    }

    public function getExperienceAttribute()
    {
        $exp_data = Helper::getExperience($this);
        return (!empty($exp_data['total_experience']) && $exp_data['total_experience'] != "0.0") ? $exp_data['total_experience'] : '';
    }

    public function scopeBasicColumns($query)
    {
        return $query->select('id','first_name','last_name','full_name','profile_image');
    }
}

User::deleting(function ($user) {
    $user->officialUser()->delete();
    $user->userEducation()->delete();
    $user->userExperience()->delete();
    $user->userBank()->delete();
    $user->userFamily()->delete();
});
