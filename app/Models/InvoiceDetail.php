<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    protected $table = 'invoicedetails';
    protected $primaryKey = 'DetailID';
    protected $fillable = ['InvoiceID', 'ProductID', 'Quantity', 'PriceID'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'InvoiceID');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductID');
    }

    public function pricing()
    {
        return $this->belongsTo(Pricing::class, 'PriceID');
    }
}

