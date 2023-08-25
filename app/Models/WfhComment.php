<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WfhComment extends Model
{
    use HasFactory;

    protected $table = 'wfh_comments';

    public function reviewUser()
    {
        return $this->belongsTo(User::class, 'review_by', 'id')->withBlocked();
    }
}
