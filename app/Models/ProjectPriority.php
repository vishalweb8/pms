<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectPriority extends Model
{
    use HasFactory;
    protected $table = 'project_priority';
    protected $fillable = [
        'name',
        'status'
    ];
}
