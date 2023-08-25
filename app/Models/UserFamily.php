<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserFamily extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'user_family_details';

    protected $fillable = [
        'user_id', 'name','relation','occupation','contact_number'
    ];
    protected $dates = ['created_at','updated_at','deleted_at'];
}
