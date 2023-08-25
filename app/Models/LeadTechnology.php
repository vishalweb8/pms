<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadTechnology extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'lead_technology';
    protected $fillable = [
        'lead_id',
        'technology_id'
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'id', 'lead_status_id');
    }
}
