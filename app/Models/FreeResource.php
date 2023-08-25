<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FreeResource extends Model
{
    use HasFactory, SoftDeletes;

    public $table = "free_resource";

    protected $fillable = [
        'user_id','daily_task_id', 'task_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->with('officialUser.userTeam');
    }

    public function taskDetail()
    {
        return $this->belongsTo(DailyTask::class, 'daily_task_id', 'id');
    }
}
