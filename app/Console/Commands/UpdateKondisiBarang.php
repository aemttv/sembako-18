<?php

namespace App\Console\Commands;

use App\Models\BarangDetail;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateKondisiBarang extends Command
{
    protected $signature = 'barang:update-kondisi';
    protected $description = 'Update kondisiBarang in BarangDetail based on tglKadaluarsa and tglMasuk';

    public function handle()
    {
        $details = BarangDetail::all();
        $now = Carbon::now();

        foreach ($details as $detail) {
            if ($detail->tglKadaluarsa && $detail->tglMasuk) {
                $tglMasuk = Carbon::parse($detail->tglMasuk);
                $tglKadaluarsa = Carbon::parse($detail->tglKadaluarsa);

                if ($tglKadaluarsa->isPast()) {
                    $newKondisi = 'Kadaluarsa';
                } elseif ($tglMasuk->diffInDays($now, false) < 7 && $tglKadaluarsa->greaterThan($now)) {
                    $newKondisi = 'Mendekati Kadaluarsa';
                } else {
                    $newKondisi = 'Baik';
                }

                if ($detail->kondisiBarang !== $newKondisi) {
                    $detail->kondisiBarang = $newKondisi;
                    $detail->save();
                    $this->info("Updated ID {$detail->idDetailBarang} to {$newKondisi}");
                }
            }
        }

        $this->info('Kondisi barang update completed.');
    }
}
