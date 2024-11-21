<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PickupOrderStatus extends Model
{
    use HasFactory;

    protected $table = 'pickup_order_status';

    protected $fillable = [
        'invoice_id', 
        'status',
        'updated_at',
        'created_at',
        'updated_by'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'InvoiceID');
    }
}
