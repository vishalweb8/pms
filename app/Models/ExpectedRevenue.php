<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpectedRevenue extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'month',
        'year',
        'actual_revenue',
        'expected_revenue'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
