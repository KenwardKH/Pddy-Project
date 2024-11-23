<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    protected $table = 'invoicedetails';
    protected $primaryKey = 'DetailID';
    protected $fillable = ['InvoiceID', 'productName','productImage', 'Quantity','productUnit', 'price'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'InvoiceID');
    }

}

