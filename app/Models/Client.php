<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\City;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'clients';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'address1',
        'address2',
        'city_id',
        'state_id',
        'country_id',
        'zipcode',
        'other_details'
    ];

    public function getFullNameAttribute()
    {
        $full_name  = $this->first_name;
        if(!empty($this->last_name)) {
            $full_name .= " " . $this->last_name;
        }
        return $full_name;
    }

    public function getFullAddressAttribute()
    {
        $full_address  = implode(", ",array_filter([$this->address1,($this->city) ? $this->city->name : '' ,($this->state) ? $this->state->name : '',($this->country) ? $this->country->name : '']));
        return $full_address;
    }

    public function city()
    {
        return $this->belongsTo("App\Models\City",'city_id');
    }
    public function state()
    {
        return $this->belongsTo("App\Models\State",'state_id');
    }
    public function country()
    {
        return $this->belongsTo("App\Models\Country",'country_id');
    }

    public function getProjectByClient()
    {
        return $this->hasMany("App\Models\Project",'client_id');
    }

}
