<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionLog extends Model
{
    use HasFactory;

    // Specify the table name if it does not match Laravel's naming convention
    protected $table = 'transaction_log';

    // Define the primary key if it is not 'id'
    protected $primaryKey = 'TransactionID';

    // Specify if the primary key is not auto-incrementing
    public $incrementing = false;

    // Set the data type of the primary key
    protected $keyType = 'int';

    // Define the fillable properties to allow mass assignment
    protected $fillable = [
        'InvoiceID',
        'CustomerID',
        'TotalAmount',
        'TransactionDate'
    ];

    // Specify any columns that should be cast to specific data types
    protected $casts = [
        'TotalAmount' => 'decimal:2',
        'TransactionDate' => 'datetime',
    ];

    // Define relationships if needed
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'CustomerID');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'InvoiceID');
    }
}
