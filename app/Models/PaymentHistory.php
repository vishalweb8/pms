<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'client_id',
        'currency_id',
        'invoice_date',
        'due_date',
        'billing_regular_date',
        'amount',
        'expected_amount',
        'amount_in_doller',
        'invoice_no',
        'bank_account',
        'payment_mode',
        'payment_platform',
        'invoice_url',
        'billing_address',
        'invoice_description',
        'bank_details',
    ];

    protected $appends = ['invoice_fullurl'];

    public function getInvoiceFullurlAttribute($value)
    {
        $url = null;
        if(!empty($this->invoice_url) && \Storage::disk('public_uploads')->exists($this->invoice_url)) {
            $url = asset('storage/'.$this->invoice_url);
        }        
        return $url;
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
