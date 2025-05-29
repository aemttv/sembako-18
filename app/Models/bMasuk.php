<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class bMasuk extends Model
{
    use Notifiable;

    // Specify the table name
    protected $table = 'barang_masuk';

    // Specify the primary key
    protected $primaryKey = 'idBarangMasuk';

    // If your primary key is not an auto-incrementing integer
    public $incrementing = false;

    // Specify the fillable columns for mass-assignment
    protected $fillable = ['idBarangMasuk','idSupplier', 'idAkun', 'tglMasuk', 'nota',];

    // Specify if you're using timestamps or not
    public $timestamps = true;

    // You can also set the default guard if needed
    protected $guard = 'web';

    public static function generateNewIdBarangMasuk()
    {
        return DB::transaction(function () {
            $latest = DB::table('barang_masuk')
                ->lockForUpdate()
                ->orderBy('idBarangMasuk', 'desc')
                ->first();
    
            if ($latest) {
                $last = intval(substr($latest->idBarangMasuk, 2)); // Strip "BM"
                return 'BM' . str_pad($last + 1, 3, '0', STR_PAD_LEFT);
            }
    
            return 'BM001';
        });
    }

    function akun() {
        return $this->belongsTo(Akun::class, 'idAkun', 'idAkun');
    }

    public function detailMasuk()
    {
        return $this->hasMany(bMasukDetail::class, 'idBarangMasuk', 'idBarangMasuk');
    }

}
