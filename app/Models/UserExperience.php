<?php

namespace App\Models;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\UserDesignation;

class UserExperience extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'user_experience_details';

    protected $fillable = [
        'user_id', 'previous_company','joined_date','released_date','designation_id'
    ];
    protected $dates = ['created_at','updated_at','deleted_at'];

    public function setJoinedDateAttribute($value)
    {
        $this->attributes['joined_date'] = Helper::setDateFormat($value);
    }

    public function setReleasedDateAttribute($value)
    {
        $this->attributes['released_date'] = Helper::setDateFormat($value);
    }

    public function getJoinedDateAttribute($value)
    {
        return Helper::getDateFormat($value);
    }

    public function getReleasedDateAttribute($value)
    {
        return Helper::getDateFormat($value);
    }

    public function userDesignation()
    {
        return $this->belongsTo(userDesignation::class,'designation_id');
    }
}
