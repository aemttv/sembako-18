<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class bRetur extends Model
{
    use Notifiable;

    // Specify the table name
    protected $table = 'retur_barang';

    // Specify the primary key
    protected $primaryKey = 'idBarangRetur';

    // If your primary key is not an auto-incrementing integer
    public $incrementing = false;

    // Specify the fillable columns for mass-assignment
    protected $fillable = [
        'idBarangRetur',
        'tglRetur',
        'idSupplier',
        'penanggungJawab',
        'statusRetur',
    ];

    // Specify if you're using timestamps or not
    public $timestamps = true;

    // You can also set the default guard if needed
    protected $guard = 'web';

    public static function generateNewIdReturBarang()
    {
        return DB::transaction(function () {
            $latest = DB::table('retur_barang')
                ->lockForUpdate()
                ->orderBy('idBarangRetur', 'desc')
                ->first();
    
            if ($latest) {
                $last = intval(substr($latest->idBarangRetur, 2)); // Strip "BK"
                return 'R' . str_pad($last + 1, 3, '0', STR_PAD_LEFT);
            }
    
            return 'R001';
        });
    }

    function supplier() {
        return $this->belongsTo(Supplier::class, 'idSupplier', 'idSupplier');
    }

    public function detailRetur()
    {
        return $this->hasMany(bReturDetail::class, 'idBarangRetur', 'idBarangRetur');
    }
    
}
