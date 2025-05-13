<?php

namespace App;

enum Kategori : int
{
    case Kebutuhan_Harian = 1;
    case Perawatan_Kebersihan = 2;
    case Produk_Kesehatan = 3;
    case Peralatan_Sekolah = 4;
    case Aksesoris_Fashion = 5;
    case Aksesoris_Hiasan = 6;

    public function namaKategori(): string
    {
        return match($this) {
            self::Kebutuhan_Harian => 'Kebutuhan Harian',
            self::Perawatan_Kebersihan => 'Perawatan & Kebersihan',
            self::Produk_Kesehatan => 'Produk Kesehatan',
            self::Peralatan_Sekolah => 'Peralatan Sekolah',
            self::Aksesoris_Fashion => 'Aksesoris Fashion',
            self::Aksesoris_Hiasan => 'Aksesoris Hiasan',
        };
    }
}
