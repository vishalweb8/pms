<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'events';
    // protected $dates = ['event_date'];
    protected $time = ['start_time','end_time'];

    protected $fillable = [
        'event_name',
        'event_date',
        'start_time',
        'end_time',
        'description',
        'file_url'
    ];

    public function setEventDateAttribute($value)
    {
        if($value != null || $value != '0000-00-00'){
            $this->attributes['event_date'] = Carbon::parse($value)->format('Y-m-d');
        }
    }

    public function getEventDateAttribute($value)
    {
        if($value == null || $value == '0000-00-00'){
            return '';
        }
        return Carbon::parse($value)->format('d-m-Y');
    }

    public function getStartTimeAttribute($value)
    {
        return Carbon::parse($value)->format('h:i A');
    }

    public function getEndTimeAttribute($value)
    {
        return Carbon::parse($value)->format('h:i A');
    }

}
