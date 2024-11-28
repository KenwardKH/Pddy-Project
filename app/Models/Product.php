<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'ProductID';
    protected $fillable = ['ProductName', 'Description', 'ProductUnit','CurrentStock','image'];

    public function pricing()
    {
        return $this->hasOne(Pricing::class, 'ProductID');
    }

    public function supplyInvoiceDetail()
    {
        return $this->hasMany(SupplyInvoiceDetail::class, 'ProductID');
    }

    public function customerCart()
    {
        return $this->hasMany(CustomerCart::class, 'ProductID');
    }
    public function cashierCart()
    {
        return $this->hasMany(cashierCart::class, 'ProductID');
    }
}

