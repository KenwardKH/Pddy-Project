<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatusLog extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'order_status_logs';

    // Primary key
    protected $primaryKey = 'id';

    // Kolom yang dapat diisi (mass-assignable)
    protected $fillable = [
        'invoice_id',
        'order_type',
        'previous_status',
        'new_status',
        'cashier_id',
        'cashier_name',
        'updated_at',
    ];

}
