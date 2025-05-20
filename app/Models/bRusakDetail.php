<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class bRusakDetail extends Model
{
    use Notifiable;

    // Specify the table name
    protected $table = 'detail_barang_rusak';

    // Specify the primary key
    protected $primaryKey = 'idDetailBR';

    // If your primary key is not an auto-incrementing integer
    public $incrementing = false;

    // Specify the fillable columns for mass-assignment
    protected $fillable = [
        'idDetailBR',
        'idBarangRusak',
        'idBarang',
        'jumlah',
        'kategoriAlasan',
        'keterangan',
    ];

    // Specify if you're using timestamps or not
    public $timestamps = true;

    // You can also set the default guard if needed
    protected $guard = 'web';

    /**
     * Generates a new idDetailRetur that doesn't already exist in the table.
     * 
     * This function is atomic and thread-safe, so it is safe to call it from
     * multiple threads or processes concurrently.
     * 
     * @return string The newly generated idDetailRetur.
     */
    public static function generateNewIdDetailBR()
    {
        return DB::transaction(function () {
            do {
                $latest = DB::table('detail_barang_rusak')
                    ->lockForUpdate()
                    ->orderBy('idDetailBR', 'desc')
                    ->first();
    
                if ($latest) {
                    $last = intval(substr($latest->idDetailBR, 3)); // Strip "DBK"
                    $newId = 'DBR' . str_pad($last + 1, 3, '0', STR_PAD_LEFT);
                } else {
                    $newId = 'DBR001';
                }
    
                $exists = DB::table('detail_barang_rusak')->where('idDetailBR', $newId)->exists();
            } while ($exists);
    
            return $newId;
        });
    }

    public function barangRusak()
    {
        return $this->belongsTo(bRusak::class, 'idBarangRusak');
    }
}
