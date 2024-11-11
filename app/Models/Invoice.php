<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';
    protected $primaryKey = 'InvoiceID';
    protected $fillable = ['CustomerID', 'InvoiceDate', 'DueDate'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'CustomerID');
    }

    public function invoiceDetails()
    {
        return $this->hasMany(InvoiceDetail::class, 'InvoiceID');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'InvoiceID');
    }
}
