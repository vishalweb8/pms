<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Holiday extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'holidays';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'date',
    ];

    public function getDateAttribute($value)
    {
        if($value == null || $value == '0000-00-00'){
            return '';
        }
        return Carbon::parse($value)->format('d-m-Y');
    }

    public function setDateAttribute($value)
    {
        if($value != null || $value != '0000-00-00'){
            $this->attributes['date'] = Carbon::parse($value);
        }else{
            $value = null;
        }
    }
}
