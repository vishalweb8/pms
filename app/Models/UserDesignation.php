<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDesignation extends Model
{
    use HasFactory;

    protected $table = 'user_designation';

    protected $fillable = [
        'name', 'status','designation_code'
    ];
}
