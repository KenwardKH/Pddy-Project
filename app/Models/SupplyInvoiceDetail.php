<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplyInvoiceDetail extends Model
{
    protected $table = 'supply_invoice_details';
    protected $primaryKey = 'SupplyInvoiceDetailId';
    public $timestamps = false;
    protected $fillable = ['SupplyInvoiceId', 'ProductID', 'ProductName', 'Quantity', 'SupplyPrice'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductID');
    }

    public function supplyInvoice()
    {
        return $this->belongsTo(SupplyInvoice::class, 'SupplyInvoiceId');
    }
}
