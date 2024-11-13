<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';

    protected $primaryKey = 'CustomerID';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'CustomerName',
        'CustomerAddress',
        'CustomerContact',
    ];

    // app/Models/Customer.php
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function transactionLogs()
    {
        return $this->hasMany(TransactionLog::class, 'CustomerID');
    }


}
