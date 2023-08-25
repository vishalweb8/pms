<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrganizationChart extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id','is_top_level','status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reportingTo()
    {
        return $this->belongsToMany(User::class,'organization_chart_reports','organization_chart_id','user_id');
    }

}
