<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pricing extends Model
{
    protected $table = 'pricing';
    protected $primaryKey = 'PriceID';
    protected $fillable = ['ProductID', 'UnitPrice'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductID');
    }
}
