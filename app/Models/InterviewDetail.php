<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InterviewDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'interview_details';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'total_experience',
        'current_ctc',
        'expected_ctc',
        'notice_period',
        'current_organization',
        'location',
        'reference_id',
        'resume',
        'source_by',
        'remark',
        'status'
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'reference_id')->withBlocked();
    }
}
