<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
// use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveCompensation extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_from',
        'request_to',
        'type',
        'halfday_status',
        'start_date',
        'end_date',
        'duration',
        'reason',
        'status',
        'approved_by',
        'rejected_by',
        'created_at',
        'updated_at',
        'created_by'
    ];

    public function userLeave()
    {
        return $this->belongsTo(User::class, 'request_from', 'id')->withBlocked();
    }

    public function userTeam()
    {
        return $this->belongsTo(UserOfficialDetail::class, 'request_from', 'user_id');
    }

    public function requestfromLeave()
    {
        return $this->belongsTo(User::class, 'request_to', 'id')->withBlocked();
    }

    public function userCreated()
    {
        return $this->belongsTo(User::class, 'created_by', 'id')->withBlocked();
    }


    /**
     * Mutators for change date format at the time of store value
     *
     * @param [date] $value
     * @return void
     * @date 10-09-2021 
     * @author Ravi Chauhan <ravichauhan.inexture@gmail.com> 
     */
    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = Carbon::parse($value)->format('Y-m-d');
    }

    public function setEndDateAttribute($value)
    {
        $this->attributes['end_date'] = Carbon::parse($value)->format('Y-m-d');
    }

    /**
     * Accessors methods for change date format
     *
     * @param [date] $value
     * @return date
     * @date 10-09-2021 
     * @author Ravi Chauhan <ravichauhan.inexture@gmail.com> 
     */
    public function getStartDateAttribute($value)
    {
        if ($value == null || $value == '0000-00-00') {
            return '';
        }
        return Carbon::parse($value)->format('d-m-Y');
    }

    public function getEndDateAttribute($value)
    {
        if ($value == null || $value == '0000-00-00') {
            return '';
        }
        return Carbon::parse($value)->format('d-m-Y');
    }

}
