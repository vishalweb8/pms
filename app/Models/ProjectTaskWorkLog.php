<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectTaskWorkLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'task_id',
        'user_id',
        'description',
        'log_date',
        'log_time'
    ];

    protected $dates = [
        'log_date'
    ];

    public function logProjectTask()
    {
        return $this->belongsTo(ProjectTask::class, 'task_id', 'id');
    }

    public function logUser()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withBlocked();
    }

}
