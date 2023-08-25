<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskAssignee extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'assignee_id',
    ];

    public function assigneeTask()
    {
        return $this->belongsTo(User::class, 'assignee_id', 'id');
    }

}
