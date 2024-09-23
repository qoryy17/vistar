<?php

namespace App\Helpers;

class Common
{
    public static function mask($str, $first, $last)
    {
        $len = strlen($str);
        $toShow = $first + $last;
        return substr($str, 0, $len <= $toShow ? 0 : $first) . str_repeat("*", $len - ($len <= $toShow ? 0 : $toShow)) . substr($str, $len - $last, $len <= $toShow ? 0 : $last);
    }

    public static function obfuscateEmail($email, $maskDomain = false)
    {
        $mail_parts = explode("@", $email);
        $mail_parts[0] = Common::mask($mail_parts[0], 2, 1); // show first 2 letters and last 1 letter

        if ($maskDomain) {
            $domain_parts = explode('.', $mail_parts[1]);

            $domain_parts[0] = Common::mask($domain_parts[0], 2, 1); // same here
            $mail_parts[1] = implode('.', $domain_parts);
        } else {
            $domain_parts = $mail_parts[1];
            $mail_parts[1] = $domain_parts;
        }

        return implode("@", $mail_parts);
    }

}
