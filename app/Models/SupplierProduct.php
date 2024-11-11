<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierProduct extends Model
{
    protected $table = 'supplierproducts';
    protected $primaryKey = 'SupplierProductID';
    protected $fillable = ['SupplierID', 'ProductID', 'Quantity', 'SupplyPrice', 'SupplyDate'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductID');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'SupplierID');
    }
}
