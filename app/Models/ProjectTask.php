<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectTask extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'project_id',
        'assignee_ids',
        'priority_id',
        'status'
    ];

    public function taskPriority()
    {
        return $this->belongsTo(ProjectPriority::class, 'priority_id', 'id');
    }

    public function assignees()
    {
        return $this->belongsToMany(User::class,'task_assignees','task_id','assignee_id')->withBlocked();
    }

    public function project()
    {
        return $this->belongsTo(Project::class,'project_id','id');
    }

    public function worklogs()
    {
        return $this->hasMany(ProjectTaskWorkLog::class, 'task_id', 'id');
    }
}
