<?php

namespace App\Models;

use App\Kategori;
use App\Merek;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Barang extends Model
{
    use Notifiable;

    // Specify the table name
    protected $table = 'barang';

    // Specify the primary key
    protected $primaryKey = 'idBarang';

    // If your primary key is not an auto-incrementing integer
    public $incrementing = false;

    // Specify the fillable columns for mass-assignment
    protected $fillable = ['namaBarang', 'kategoriBarang', 'merekBarang', 'stokBarang', 'satuanBarang', 'tglMasuk', 'kondisiBarang', 'hargaBeli', 'hargaJual', 'gambarProduk', 'barcode', 'statusBarang'];

    // Specify if you're using timestamps or not
    public $timestamps = true;

    // You can also set the default guard if needed
    protected $guard = 'web';

    protected $casts = [
        'kategoriBarang' => Kategori::class
    ];

    public static function generateNewIdBarang()
    {
        $latest = self::orderBy('idBarang', 'desc')->first();
        if ($latest) {
            $last = intval(substr($latest->idBarang, 1)); // strip "S"
            return 'B' . str_pad($last + 1, 3, '0', STR_PAD_LEFT);
        }
        return 'B001';
    }

    public function detailBarang()
    {
        return $this->hasMany(BarangDetail::class, 'idBarang', 'idBarang')->latest('tglMasuk');
    }

    public function latestDetailBarang()
    {
        return $this->hasOne(BarangDetail::class, 'idBarang')->latest('tglMasuk');
    }

    public function merek()
    {
        return $this->belongsTo(bMerek::class, 'merekBarang', 'idMerek');
    }
}
