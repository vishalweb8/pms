<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectPaymentType extends Model
{
    use HasFactory;
    protected $table = 'project_payment_type';
    protected $fillable = [
        'type',
        'status'
    ];
}
