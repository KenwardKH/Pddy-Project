<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryOrderStatus extends Model
{
    use HasFactory;

    protected $table = 'delivery_order_status';

    protected $fillable = [
        'invoice_id', 
        'status',
        'alamat',
        'updated_at',
        'created_at'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
