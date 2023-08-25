<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Policy extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'policies';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'title',
        'status',
        'file_url'
    ];
}
