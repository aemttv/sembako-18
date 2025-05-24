<?php

namespace App\Models;

use App\enum\Alasan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class bKeluar extends Model
{
    use Notifiable;

    // Specify the table name
    protected $table = 'barang_keluar';

    // Specify the primary key
    protected $primaryKey = 'idBarangKeluar';

    // If your primary key is not an auto-incrementing integer
    public $incrementing = false;

    // Specify the fillable columns for mass-assignment
    protected $fillable = [
        'idBarangKeluar',
        'invoice',
        'idAkun',
        'tglKeluar',
    ];

    // Specify if you're using timestamps or not
    public $timestamps = true;

    // You can also set the default guard if needed
    protected $guard = 'web';

    public static function generateNewIdBarangKeluar()
    {
        return DB::transaction(function () {
            $latest = DB::table('barang_keluar')
                ->lockForUpdate()
                ->orderBy('idBarangKeluar', 'desc')
                ->first();
    
            if ($latest) {
                $last = intval(substr($latest->idBarangKeluar, 2)); // Strip "BK"
                return 'BK' . str_pad($last + 1, 3, '0', STR_PAD_LEFT);
            }
    
            return 'BK001';
        });
    }

    public static function generateNewInvoiceNumber()
    {
        return DB::transaction(function () {
            $datePrefix = now()->format('Ymd'); // Today's date: 20250514
            $fullPrefix = 'INV' . $datePrefix;

            // Find the latest invoice with today's prefix
            $latest = DB::table('barang_keluar')
                ->where('invoice', 'like', $fullPrefix . '%')
                ->lockForUpdate()
                ->orderBy('invoice', 'desc')
                ->first();

            if ($latest && isset($latest->invoice)) {
                $lastIncrement = intval(substr($latest->invoice, -3)); // Last 3 digits
                $newIncrement = $lastIncrement + 1;
            } else {
                $newIncrement = 1;
            }

            return $fullPrefix . '-' . str_pad($newIncrement, 3, '0', STR_PAD_LEFT);
        });
    }

    public function detailKeluar()
    {
        return $this->hasMany(bKeluarDetail::class, 'idBarangKeluar', 'idBarangKeluar');
    }

}
