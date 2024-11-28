<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    // Nama tabel
    protected $table = 'suppliers';

    // Primary key
    protected $primaryKey = 'SupplierID';

    // Apakah menggunakan timestamps (created_at, updated_at)
    public $timestamps = false;

    // Mass assignable attributes
    protected $fillable = [
        'SupplierName',
        'SupplierAddress',
        'SupplierContact',
    ];
}
