<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'ProductID';
    protected $fillable = ['ProductName', 'Description', 'CurrentStock', 'CategoryID'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'CategoryID');
    }

    public function pricing()
    {
        return $this->hasOne(Pricing::class, 'ProductID');
    }

    public function supplierProducts()
    {
        return $this->hasMany(SupplierProduct::class, 'ProductID');
    }

    public function customerCart()
    {
        return $this->hasMany(CustomerCart::class, 'ProductID');
    }
}
