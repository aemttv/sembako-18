<?php

namespace App\enum;

enum KondisiBarang : string
{
    case Baik = 'baik';
    case MendekatiKadaluarsa = 'mendekati_kadaluarsa';
    case Kadaluarsa = 'kadaluarsa';

    public function namaKondisi(): string
    {
        return match($this) {
            self::Baik => 'Baik',
            self::MendekatiKadaluarsa => 'Mendekati Kadaluarsa',
            self::Kadaluarsa => 'Kadaluarsa',
        };
    }
}
