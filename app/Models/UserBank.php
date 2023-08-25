<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBank extends Model
{
    use HasFactory;

    protected $table = 'user_bank_details';

    protected $fillable = [
        'user_id', 'personal_bank_name','personal_bank_ifsc_code','personal_account_number','salary_bank_name','salary_bank_ifsc_code','salary_account_number','pan_number','aadharcard_name','aadharcard_number'
    ];
    protected $dates = ['created_at','updated_at','deleted_at'];
}
