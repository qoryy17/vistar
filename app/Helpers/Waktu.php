<?php

namespace App\Helpers;

use DateTime;

class Waktu
{

    public static function sesiWaktu()
    {
        $time = new DateTime();
        $hour = $time->format('H');

        if ($hour >= 5 && $hour < 12) {
            return 'Pagi'; // 05:00 - 11:59
        } elseif ($hour >= 12 && $hour < 15) {
            return 'Siang'; // 12:00 - 14:59
        } elseif ($hour >= 15 && $hour < 18) {
            return 'Sore'; // 15:00 - 17:59
        } else {
            return 'Malam'; // 18:00 - 04:59
        }
    }
}
