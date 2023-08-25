<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamLeaderMember extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'member_id'];

    public $timestamps = false;
}
