<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancelledTransaction extends Model
{
    use HasFactory;

    protected $table = 'cancelled_transaction'; // Nama tabel

    public $timestamps = false; // Nonaktifkan fitur timestamp otomatis

    protected $fillable = [
        'InvoiceId',
        'cancellation_reason',
        'cancelled_by',
        'cancellation_date',
    ];

    /**
     * Relasi ke tabel invoices.
     * Many-to-One relationship (relasi balik dari CancelledTransaction ke Invoice).
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'InvoiceId');
    }
}
