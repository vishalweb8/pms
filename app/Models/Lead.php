<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    public $table = "leads";

    protected $fillable = [
        'lead_title','company_name', 'first_name', 'last_name', 'email', 'phone', 'skype_id', 'website', 'lead_industry_id', 'lead_source_id', 'lead_status_id', 'lead_owner_id', 'city_id', 'state_id', 'country_id', 'description'
    ];

    public function insertUpdate($data)
    {
         if (isset($data['id']) && $data['id'] != '' && $data['id'] > 0) {
            $updateData = [];
            foreach ($this->fillable as $field) {
                if (array_key_exists($field, $data)) {
                    $updateData[$field] = $data[$field];
                }
            }
            $lead = Lead::where('id', $data['id'])->first();
            $lead->update($updateData);
        } else {
			$lead = Lead::create($data);
        }
        return $lead;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'lead_owner_id', 'id')->withBlocked();
    }

    public function leadSource()
    {
        return $this->hasOne(LeadSource::class, 'id', 'lead_source_id');
    }

    public function leadStatus()
    {
        return $this->hasOne(LeadStatus::class, 'id', 'lead_status_id');
    }

    public function industry()
    {
        return $this->hasOne(Industry::class, 'id', 'lead_industry_id');
    }

    public function technology()
    {
        return $this->belongsToMany(Technology::class, 'lead_technology', 'lead_id', 'technology_id')
                    ->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(LeadComment::class, 'lead_id', 'id')->orderby('id','desc')->with('user');
    }

    public function city()
    {
        return $this->hasOne(City::class, 'id', 'city_id');
    }
    public function state()
    {
        return $this->hasOne(State::class, 'id', 'state_id');
    }
    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }


    public function processTechnology($data, $lead) {
        if($data == '') {
            $lead->technology()->detach();
        }else {
            $currentSkills = array_filter($data, 'is_numeric');
            $lead->technology()->sync($currentSkills);
        }
    }



}
