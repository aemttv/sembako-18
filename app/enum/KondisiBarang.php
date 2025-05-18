<?php

namespace App\enum;

enum KondisiBarang : string
{
    case Baik = 'layak';
    case MendekatiKadaluarsa = 'mendekati_kadaluarsa';
    case Kadaluarsa = 'kadaluarsa';

    public function namaKondisi(): string
    {
        return match($this) {
            self::Baik => 'Layak',
            self::MendekatiKadaluarsa => 'Mendekati Kadaluarsa',
            self::Kadaluarsa => 'Kadaluarsa',
        };
    }
}
