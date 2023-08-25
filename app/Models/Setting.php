<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Setting extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'settings';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'field_name','value','field_label'
    ];
}
