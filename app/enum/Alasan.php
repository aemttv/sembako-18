<?php

namespace App\enum;

enum Alasan : int
{
    case Terjual = 1;
    case Pribadi = 2;

    public function alasan(): string
    {
        return match($this) {
            self::Terjual => 'Terjual',
            self::Pribadi => 'Pribadi'
        };
    }
}
