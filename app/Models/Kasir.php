<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kasir extends Model
{
    // Nama tabel yang sesuai dengan database
    protected $table = 'kasir';

    // Menentukan kolom primary key jika menggunakan nama selain id
    protected $primaryKey = 'id_kasir';

    // Menentukan apakah timestamps harus diaktifkan (laravel default: created_at dan updated_at)
    public $timestamps = false;  // Karena di tabel tidak ada kolom timestamps (created_at, updated_at)

    // Menentukan kolom mana yang bisa diisi secara mass-assignment
    protected $fillable = [
        'user_id',
        'nama_kasir',
        'alamat_kasir',
        'kontak_kasir',
    ];

    // Jika kasir berelasi dengan model lain, misalnya 'User', Anda bisa menambah relasi seperti ini:
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    
}
