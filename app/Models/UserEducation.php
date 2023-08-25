<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserEducation extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'user_education_details';

    protected $fillable = [
        'user_id', 'qualification','university_board','grade','passing_year','deleted_at'
    ];
    protected $dates = ['created_at','updated_at','deleted_at'];
}
