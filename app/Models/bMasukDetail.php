<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class bMasukDetail extends Model
{
    use Notifiable;

    // Specify the table name
    protected $table = 'detail_barang_masuk';

    // Specify the primary key
    protected $primaryKey = 'idDetailBM';

    // If your primary key is not an auto-incrementing integer
    public $incrementing = false;

    // Specify the fillable columns for mass-assignment
    protected $fillable = ['idDetailBM', 'idBarangMasuk', 'idBarang', 'jumlahMasuk', 'hargaBeli', 'subtotal', 'tglKadaluarsa'];

    // Specify if you're using timestamps or not
    public $timestamps = true;

    // You can also set the default guard if needed
    protected $guard = 'web';

    public static function generateNewIdDetailBM()
    {
        return DB::transaction(function () {
            do {
                $latest = DB::table('detail_barang_masuk')
                    ->lockForUpdate()
                    ->orderBy('idDetailBM', 'desc')
                    ->first();
    
                if ($latest) {
                    $last = intval(substr($latest->idDetailBM, 3)); // Strip "DBM"
                    $newId = 'DBM' . str_pad($last + 1, 3, '0', STR_PAD_LEFT);
                } else {
                    $newId = 'DBM001';
                }
    
                $exists = DB::table('detail_barang_masuk')->where('idDetailBM', $newId)->exists();
            } while ($exists);
    
            return $newId;
        });
    }

    public function barangMasuk()
    {
        return $this->belongsTo(bMasuk::class, 'idBarangMasuk');
    }
}
