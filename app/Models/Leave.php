<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_from',
        'request_to',
        'type',
        'halfday_status',
        'start_date',
        'end_date',
        'return_date',
        'duration',
        'reason',
        'isadhoc_leave',
        'adhoc_status',
        'available_on_phone',
        'available_on_city',
        'emergency_contact',
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
    public function activeUser()
    {
        return $this->belongsTo(User::class, 'request_from', 'id');
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
    public function comments()
    {
        return $this->hasMany(LeaveComment::class);
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

    public function setReturnDateAttribute($value)
    {
        $this->attributes['return_date'] = Carbon::parse($value)->format('Y-m-d');
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

    public function getReturnDateAttribute($value)
    {
        if ($value == null || $value == '0000-00-00') {
            return '';
        }
        return Carbon::parse($value)->format('d-m-Y');
    }

    public function scopeFinancialYear($query, $finYear)
    {
        $startYear = currentFinStartYear();
        $endYear = $startYear+1;
        if(!empty($finYear)) {
            $financial_year = explode('-',$finYear);
            $startYear = $financial_year[0] ?? $startYear;
            $endYear = $financial_year[1] ?? $endYear;
        }

        return $query->where(function($qry) use($startYear,$endYear){
            $qry->whereBetween('start_date',[$startYear.'-04-01',$endYear.'-03-31']);
        });

    }
}
