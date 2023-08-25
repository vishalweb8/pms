<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveCompensationComment extends Model
{
    use HasFactory;

    public function reviewUser()
    {
        return $this->belongsTo(User::class, 'review_by', 'id')->withBlocked();
    }
}
