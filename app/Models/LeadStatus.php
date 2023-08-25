<?php

namespace App\Models;

use App\Http\Traits\ActiveTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadStatus extends Model
{
    use HasFactory, SoftDeletes, ActiveTrait;
    protected $table = 'lead_status';
    protected $fillable = [
        'name',
        'status'
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'id', 'lead_status_id');
    }
}
