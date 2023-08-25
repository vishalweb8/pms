<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectAllocation extends Model
{
    use HasFactory;

    protected $table = 'project_allocation';

    protected $fillable = [
        'type',
        'status',
    ];
}
