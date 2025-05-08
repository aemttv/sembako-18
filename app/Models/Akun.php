<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Akun extends Authenticatable
{
    use Notifiable;

    // Specify the table name
    protected $table = 'akun';

    // Specify the primary key
    protected $primaryKey = 'idAkun';

    // If your primary key is not an auto-incrementing integer
    public $incrementing = false;

    // Specify the fillable columns for mass-assignment
    protected $fillable = ['nama', 'password', 'nohp', 'email', 'alamat', 'peran', 'statusAkun'];

    // Specify if you're using timestamps or not
    public $timestamps = true;

    // You can also set the default guard if needed
    protected $guard = 'web';

    protected $rememberTokenName = null;

    public static function generateNewId()
    {
        $latest = self::orderBy('idAkun', 'desc')->first();
        if ($latest) {
            $last = intval(substr($latest->idAkun, 1)); // strip "A"
            return 'A' . str_pad($last + 1, 3, '0', STR_PAD_LEFT);
        }
        return 'A001';
    }
}
