<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class bRusak extends Model
{
    use Notifiable;

    // Specify the table name
    protected $table = 'barang_rusak';

    // Specify the primary key
    protected $primaryKey = 'idBarangRusak';

    // If your primary key is not an auto-incrementing integer
    public $incrementing = false;

    // Specify the fillable columns for mass-assignment
    protected $fillable = [
        'idBarangRusak',
        'tglRusak',
        'penanggungJawab',
        'statusRusak',
    ];

    // Specify if you're using timestamps or not
    public $timestamps = true;

    // You can also set the default guard if needed
    protected $guard = 'web';

    public static function generateNewIdBarangRusak()
    {
        return DB::transaction(function () {
            $latest = DB::table('barang_rusak')
                ->lockForUpdate()
                ->orderBy('idBarangRusak', 'desc')
                ->first();
    
            if ($latest) {
                $last = intval(substr($latest->idBarangRusak, 2)); // Strip "BK"
                return 'BR' . str_pad($last + 1, 3, '0', STR_PAD_LEFT);
            }
    
            return 'BR001';
        });
    }

    public function detailRusak()
    {
        return $this->hasMany(bRusakDetail::class, 'idBarangRusak', 'idBarangRusak');
    }
}
