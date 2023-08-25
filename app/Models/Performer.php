<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Performer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','month_year','revenue','expense','status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
