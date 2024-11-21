<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashierCart extends Model
{
    // Table name
    protected $table = 'cashier_cart';

    // Primary key
    protected $primaryKey = 'CartID';

    // Disable timestamps (created_at, updated_at)
    public $timestamps = false;

    // Columns that can be mass-assigned
    protected $fillable = [
        'CashierID',
        'ProductID',
        'Quantity',
    ];

    /**
     * Relationship to the kasir model.
     */
    public function cashier()
    {
        return $this->belongsTo(Cashier::class, 'CashierID', 'id_kasir');
    }

    /**
     * Relationship to the Product model.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductID', 'ProductID');
    }
}
