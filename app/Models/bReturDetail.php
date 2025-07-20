<?php

namespace App\Models;

use App\enum\Alasan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class bReturDetail extends Model
{
    use Notifiable;

    // Specify the table name
    protected $table = 'detail_retur_barang';

    // Specify the primary key
    protected $primaryKey = 'idDetailRetur';

    // If your primary key is not an auto-incrementing integer
    public $incrementing = false;

    // Specify the fillable columns for mass-assignment
    protected $fillable = [
        'idDetailRetur', 
        'idBarangRetur', 
        'idBarang', 
        'jumlah', 
        'kategoriAlasan', 
        'keterangan',
        'statusReturDetail',
    ];

    // Specify if you're using timestamps or not
    public $timestamps = true;

    // You can also set the default guard if needed
    protected $guard = 'web';

    protected $casts = [
        'kategoriAlasan' => Alasan::class
    ];

    /**
     * Generates a new idDetailRetur that doesn't already exist in the table.
     * 
     * This function is atomic and thread-safe, so it is safe to call it from
     * multiple threads or processes concurrently.
     * 
     * @return string The newly generated idDetailRetur.
     */
    public static function generateNewIdDetailRetur()
    {
        return DB::transaction(function () {
            do {
                $latest = DB::table('detail_retur_barang')
                    ->lockForUpdate()
                    ->orderBy('idDetailRetur', 'desc')
                    ->first();
    
                if ($latest) {
                    $last = intval(substr($latest->idDetailRetur, 3)); // Strip "DR"
                    $newId = 'DR' . str_pad($last + 1, 3, '0', STR_PAD_LEFT);
                } else {
                    $newId = 'DR001';
                }
    
                $exists = DB::table('detail_retur_barang')->where('idDetailRetur', $newId)->exists();
            } while ($exists);
    
            return $newId;
        });
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'idBarang', 'idBarang');
    }

    public function detailBarangRetur()
    {
        return $this->belongsTo(BarangDetail::class, 'barcode', 'barcode');
    }

    public function returBarang()
    {
        return $this->belongsTo(bRetur::class, 'idBarangRetur', 'idBarangRetur');
    }

}
