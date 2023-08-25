<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class projectTechnology extends Model
{
    use HasFactory;

    protected $table = 'project_technology';

    protected $fillable = [
        'project_id', 'technology_id'
    ];
    public $timestamps = false;

}
