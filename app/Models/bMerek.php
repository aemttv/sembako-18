<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class bMerek extends Model
{
    protected $table = 'merek_barang';

    protected $fillable = ['idMerek','namaMerek'];

    // Specify if you're using timestamps or not
    public $timestamps = true;

}
