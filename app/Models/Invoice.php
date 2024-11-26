<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';
    protected $primaryKey = 'InvoiceID';
    protected $fillable = [
        'CustomerID',
        'customerName', 
        'customerContact', 
        'InvoiceDate', 
        'type', 
        'payment_option',
        'CashierID',
        'CashierName'
    ];
    public $timestamps = false;

    public function invoiceDetails()
    {
        return $this->hasMany(InvoiceDetail::class, 'InvoiceID');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'InvoiceID');
    }

    public function deliveryStatus()
    {
        return $this->hasOne(DeliveryOrderStatus::class, 'invoice_id');
    }

    public function pickupStatus()
    {
        return $this->hasOne(PickupOrderStatus::class, 'invoice_id');
    }

    public function cancelledTransaction()
    {
        return $this->hasOne(CancelledTransaction::class, 'InvoiceId');
    }

}
