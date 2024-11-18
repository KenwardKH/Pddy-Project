<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    protected $table = 'invoicedetails';
    protected $primaryKey = 'DetailID';
    protected $fillable = ['invoice_id', 'productName', 'Quantity', 'price'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'InvoiceID');
    }

}

