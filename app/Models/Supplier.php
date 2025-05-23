<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Supplier extends Model
{
    use Notifiable;

    // Specify the table name
    protected $table = 'supplier';

    // Specify the primary key
    protected $primaryKey = 'idSupplier';

    // If your primary key is not an auto-incrementing integer
    public $incrementing = false;

    // Specify the fillable columns for mass-assignment
    protected $fillable = ['nama', 'nohp', 'alamat', 'status'];

    // Specify if you're using timestamps or not
    public $timestamps = true;

    // You can also set the default guard if needed
    protected $guard = 'web';

    public static function generateNewIdSupplier()
    {
        $latest = self::orderBy('idSupplier', 'desc')->first();
        if ($latest) {
            $last = intval(substr($latest->idSupplier, 1)); // strip "S"
            return 'S' . str_pad($last + 1, 3, '0', STR_PAD_LEFT);
        }
        return 'S001';
    }
}
