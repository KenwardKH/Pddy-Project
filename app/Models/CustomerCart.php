<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerCart extends Model
{
    // Table name
    protected $table = 'customer_cart';

    // Primary key
    protected $primaryKey = 'CartID';

    // Disable timestamps (created_at, updated_at)
    public $timestamps = false;

    // Columns that can be mass-assigned
    protected $fillable = [
        'CustomerID',
        'ProductID',
        'Quantity',
    ];

    /**
     * Relationship to the Customer model.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'CustomerID');
    }

    /**
     * Relationship to the Product model.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductID');
    }
}
