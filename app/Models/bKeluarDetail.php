<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class bKeluarDetail extends Model
{
    use Notifiable;

    // Specify the table name
    protected $table = 'detail_barang_keluar';

    // Specify the primary key
    protected $primaryKey = 'idDetailBK';

    // If your primary key is not an auto-incrementing integer
    public $incrementing = false;

    // Specify the fillable columns for mass-assignment
    protected $fillable = [
        'idBarangKeluar',
        'idBarang',
        'jumlahKeluar',
        'subtotal',
        'kategoriAlasan',
        'keterangan',
    ];

    // Specify if you're using timestamps or not
    public $timestamps = true;

    // You can also set the default guard if needed
    protected $guard = 'web';

    public static function generateNewIdDetailBK()
    {
        return DB::transaction(function () {
            do {
                $latest = DB::table('detail_barang_keluar')
                    ->lockForUpdate()
                    ->orderBy('idDetailBK', 'desc')
                    ->first();
    
                if ($latest) {
                    $last = intval(substr($latest->idDetailBK, 3)); // Strip "DBK"
                    $newId = 'DBK' . str_pad($last + 1, 3, '0', STR_PAD_LEFT);
                } else {
                    $newId = 'DBK001';
                }
    
                $exists = DB::table('detail_barang_keluar')->where('idDetailBK', $newId)->exists();
            } while ($exists);
    
            return $newId;
        });
    }

    public function barangKeluar()
    {
        return $this->belongsTo(bKeluar::class, 'idBarangKeluar');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'idBarang', 'idBarang');
    }
}
