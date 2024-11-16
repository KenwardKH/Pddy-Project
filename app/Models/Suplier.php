<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    // Nama tabel yang sesuai dengan database
    protected $table = 'suppliers';

    // Menentukan kolom primary key jika menggunakan nama selain id
    protected $primaryKey = 'SupplierID';

    // Menentukan apakah timestamps harus diaktifkan (laravel default: created_at dan updated_at)
    public $timestamps = false;  // Karena di tabel tidak ada kolom timestamps (created_at, updated_at)

    // Menentukan kolom mana yang bisa diisi secara mass-assignment
    protected $fillable = [
        'SupplierName',
        'SupplierAddress',
        'SupplierContact',
    ];

    // Jika tabel 'suppliers' berelasi dengan model lain, Anda bisa menambah relasi seperti ini
    public function products()
    {
        return $this->hasMany(Product::class, 'SupplierID');
    }
}
