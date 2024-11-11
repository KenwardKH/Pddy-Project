<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'PaymentID';
    protected $fillable = ['InvoiceID', 'PaymentDate', 'AmountPaid'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'InvoiceID');
    }
}
