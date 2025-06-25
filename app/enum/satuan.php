<?php

namespace App\enum;

enum satuan : int
{
    case pcs = 1;
    case kg = 2;
    case dus = 3;

    public function namaSatuan(): string
    {
        return match($this) {
            self::pcs => 'pcs/eceran',
            self::kg => 'kg',
            self::dus => 'dus'
        };
    }
}
