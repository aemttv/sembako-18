<?php

namespace App\enum;

enum Alasan : int
{
    case Terjual = 1;
    case Pribadi = 2;
    case cacat = 3;
    case tidak_sesuai = 4;
    case kadaluarsa = 5;
    case tidak_layak = 6;


    public function alasan(): string
    {
        return match($this) {
            self::Terjual => 'Terjual',
            self::Pribadi => 'Pribadi',
            self::cacat => 'Barang Cacat',
            self::tidak_sesuai => 'Tidak Sesuai',
            self::kadaluarsa => 'Kadaluarsa',
            self::tidak_layak => 'Tidak Layak',
        };
    }
}
