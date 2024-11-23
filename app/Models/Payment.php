<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';
    public $timestamps = false; // Nonaktifkan timestamps
    protected $primaryKey = 'PaymentID';
    protected $fillable = ['InvoiceID', 'PaymentDate', 'AmountPaid','PaymentImage'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'InvoiceID');
    }
}
