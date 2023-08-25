<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingSodEod extends Model
{
    use HasFactory;

    protected $table = 'pending_sod_eod_entries';
    protected $guarded = ['id'];

}
