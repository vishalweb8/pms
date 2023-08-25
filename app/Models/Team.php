<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $table = 'teams';

    protected $fillable = [
        'name',
        'team_lead_id',
        'status'
    ];

    public function teamLeader()
    {
        return $this->HasMany(User::class, 'team_lead_id')->withBlocked();
    }

    public function teamLeaders()
    {
        return $this->belongsTo(User::class, 'team_lead_id')->withBlocked();
    }

    // public function userTeam()
    // {
    //     return $this->hasOne(Team::class,'team_id');
    // }

    public function userOfficialDetail()
    {
        return $this->hasMany(UserOfficialDetail::class,'team_id')->has('activeUser');
    }

}
