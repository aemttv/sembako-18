<?php

namespace App\Models;

use App\Kondisi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class BarangDetail extends Model
{
    use Notifiable;

    // Specify the table name
    protected $table = 'detail_barang';

    // Specify the primary key
    protected $primaryKey = 'idDetailBarang';

    // If your primary key is not an auto-incrementing integer
    public $incrementing = false;

    // Specify the fillable columns for mass-assignment
    protected $fillable = ['idDetailBarang', 'idBarang', 'kondisiBarang', 'quantity', 'satuanBarang', 'hargaBeli', 'tglMasuk', 'tglKadaluarsa', 'barcode'];

    // Specify if you're using timestamps or not
    public $timestamps = true;

    // You can also set the default guard if needed
    protected $guard = 'web';

    // protected $casts = [
    //     'kondisiBarang' => Kondisi::class
    // ];

    public static function generateNewIdBarangDetail()
    {
        return DB::transaction(function () {
            do {
                $latest = DB::table('detail_barang')
                    ->lockForUpdate()
                    ->orderBy('idDetailBarang', 'desc')
                    ->first();
    
                if ($latest) {
                    $last = intval(substr($latest->idDetailBarang, 3)); // Strip "DBM"
                    $newId = 'DB' . str_pad($last + 1, 3, '0', STR_PAD_LEFT);
                } else {
                    $newId = 'DB001';
                }
    
                $exists = DB::table('detail_barang')->where('idDetailBarang', $newId)->exists();
            } while ($exists);
    
            return $newId;
        });
    }

    public function barang()
    {
        return $this->belongsTo(barang::class, 'idBarang');
    }

}
