<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingLog extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'pricing_logs';

    // Primary Key
    protected $primaryKey = 'id';

    // Kolom-kolom yang dapat diisi
    protected $fillable = [
        'ProductID',
        'OldPrice',
        'NewPrice',
        'TimeChanged',
    ];
}
