<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationChartReport extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','organization_chart_id'];

    public function organization()
    {
        return $this->belongsTo(OrganizationChart::class,'organization_chart_id');
    }
}
