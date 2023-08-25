<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectActivity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'project_id',
        'comments',
    ];

    public function activityUser()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withBlocked();
    }

    public function activityProject()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
}
