<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionList extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'transaction_list';

    // Primary key
    protected $primaryKey = 'TransactionID';

    // Kolom yang dapat diisi (mass-assignable)
    protected $fillable = [
        'InvoiceID',
        'CustomerName',
        'CustomerContact',
        'InvoiceDate',
        'DueDate',
        'PaymentOption',
        'CashierName',
        'TotalAmount',
        'PaymentDate',
        'AmountPaid',
        'OrderStatus',
    ];

}
