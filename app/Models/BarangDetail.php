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
    protected $fillable = ['idDetailBarang', 'idBarang', 'idSupplier', 'kondisiBarang', 'quantity', 'satuanBarang', 'hargaBeli', 'tglMasuk', 'tglKadaluarsa', 'barcode', 'statusBarang'];

    // Specify if you're using timestamps or not
    public $timestamps = true;

    // You can also set the default guard if needed
    protected $guard = 'web';

    /**
     * Generates a new idDetailBarang that doesn't already exist in the table.
     *
     * This function is atomic and thread-safe, so it is safe to call it from
     * multiple threads or processes concurrently.
     *
     * @return string The newly generated idDetailBarang.
     */
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

    /**
     * Generates a random barcode of a given length.
     *
     * The characters used to generate the barcode are the uppercase letters A-Z and the numbers 0-9.
     * The length of the barcode can be adjusted by modifying the value of the $length variable.
     *
     * @return string A random barcode of the specified length.
     */
    public static function generateBarcode() {
        $length = 12; // adjust the length of the barcode as needed
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $barcode = '';

        for ($i = 0; $i < $length; $i++) {
            $barcode .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $barcode;
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'idBarang');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'idSupplier');
    }

}
