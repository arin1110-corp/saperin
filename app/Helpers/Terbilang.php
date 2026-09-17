<?php

namespace App\Helpers;

class Terbilang
{
    public static function make($angka): string
    {
        $angka = (int) $angka;

        if ($angka === 0) {
            return 'nol';
        }

        $angka = abs($angka);

        $bilangan = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'];

        if ($angka < 12) {
            return $bilangan[$angka];
        }

        if ($angka < 20) {
            return self::make($angka - 10) . ' belas';
        }

        if ($angka < 100) {
            return self::make(intdiv($angka, 10)) . ' puluh ' . self::make($angka % 10);
        }

        if ($angka < 200) {
            return 'seratus ' . self::make($angka - 100);
        }

        if ($angka < 1000) {
            return self::make(intdiv($angka, 100)) . ' ratus ' . self::make($angka % 100);
        }

        if ($angka < 2000) {
            return 'seribu ' . self::make($angka - 1000);
        }

        if ($angka < 1000000) {
            return self::make(intdiv($angka, 1000)) . ' ribu ' . self::make($angka % 1000);
        }

        if ($angka < 1000000000) {
            return self::make(intdiv($angka, 1000000)) . ' juta ' . self::make($angka % 1000000);
        }

        if ($angka < 1000000000000) {
            return self::make(intdiv($angka, 1000000000)) . ' miliar ' . self::make($angka % 1000000000);
        }

        return self::make(intdiv($angka, 1000000000000)) . ' triliun ' . self::make($angka % 1000000000000);
    }
}