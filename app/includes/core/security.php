<?php

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * Updated by Dracovian
 * 
 * File: security - v0.2
 */

namespace Alphafable\Core;
require_once sprintf('%s/../global.php', __DIR__);

class Security {
    private const string METHOD = 'AES-256-CBC';
    private const string CHARSET = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    private string $nkey = 'ZorbakOwnsyou';

    public function encodeNinja(string $text) : string {
        $result = "";

        for ($i = 0; $i < strlen($text); $i++) {
            $rnd = floor((mt_rand(0, mt_getrandmax() - 1) / mt_getrandmax()) * 66) + 33;
            $key = ord($this->nkey[$i % strlen($this->nkey)]);
            $result .= base_convert((ord($text[$i]) + $rnd + $key), 10, 30) . base_convert($rnd, 10, 30);
        }

        return "<ninja2>$result</ninja2>";
    }

    public function safe_b64encode(string $str) : string {
        $data = base64_encode($str);
        return str_replace(['+', '/', '='], ['-', '_', ''], $data);
    }

    public function safe_b64decode(string $str) : string {
        $data = str_replace(['-', '_'], ['+', '/'], $str);
        $mod4 = strlen($data) % 4;

        if ($mod4) $data .= substr('====', $mod4);
        return base64_decode($data);
    }

    public function encode(string $value, string $key) : string {
        $ivsize = openssl_cipher_iv_length(self::METHOD);
        $iv = openssl_random_pseudo_bytes($ivsize);
        $cipher = openssl_encrypt($value, self::METHOD, $key, OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $cipher, $key, true);

        return trim($this->safe_b64encode($iv . $hmac . $cipher));
    }

    public function decode(string $value, string $key) : string {
        $c = $this->safe_b64decode($value);
        $ivsize = openssl_cipher_iv_length(self::METHOD);
        $iv = mb_substr($c, 0, $ivsize, '8bit');
        $hmac = mb_substr($c, $ivsize, 32, '8bit');
        $raw = mb_substr($c, $ivsize + 32, null, '8bit');
        $plain = openssl_decrypt($raw, self::METHOD, $key, OPENSSL_RAW_DATA, $iv);

        $calcmac = hash_hmac('sha256', $raw, $key, true);
        if (!hash_equals($hmac, $calcmac)) die('Invalid HMAC!');

        return trim($plain);
    }

    function generateRandom(int $length) : string {
        $output = "";

        for ($i = 0; $i < $length; $i++) {
            $index = random_int(0, strlen(self::CHARSET) - 1);
            $output .= self::CHARSET[$index];
        }

        return $this->safe_b64encode($output);
    }

    public function checkAccessLevel(int $userAccess, int $requiredAccess) : string {
        /*
         * Since we don't need the array of access statuses
         *  we can still document them for posterity sake.
         * 
         * 0  => Normal Player
         * 5  => Guardian
         * 10 => DragonLord
         * 15 => Beta Tester
         * 20 => Alpha Tester
         * 25 => Moderator
         * 30 => Staff
         * 35 => Designer
         * 40 => Programmer
         * 45 => Administrator
         * 50 => Owner
         */
        
        if ($userAccess < 0)
            return "Banned";
        
        if ($userAccess < $requiredAccess)
            return "Invalid";

        return "OK";
    }
}