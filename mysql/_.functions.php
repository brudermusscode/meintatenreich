<?php

// CONVERT TIMESTAMP
class convertToAgo
{

    function convert_datetime($str)
    {
        list($date, $time) = explode(' ', $str);
        list($year, $month, $day) = explode('-', $date);
        list($hour, $minute, $second) = explode(':', $time);
        $timestamp = mktime($hour, $minute, $second, $month, $day, $year);
        return $timestamp;
    }

    function makeAgo($timestamp)
    {
        $difference = time() - $timestamp;
        $periods = array("Sek.", "Min.", "Std.", "Tg.", "Wo.", "Mt.", "Jr.", "Ewgk.");
        $lengths = array("60", "60", "24", "7", "4.35", "12", "10");
        for ($j = 0; $difference >= $lengths[$j]; $j++)
            $difference /= $lengths[$j];
        $difference = round($difference);
        if ($difference != 1) $periods[$j] .= "";
        $text = "$difference $periods[$j] her";
        return $text;
    }
}


// TRIM TEXT
function trim_text($input, $length, $ellipses = true, $strip_html = true)
{
    //strip tags, if desired
    if ($strip_html) {
        $input = strip_tags($input);
    }

    //no need to trim, already shorter than trim length
    if (strlen($input) <= $length) {
        return $input;
    }

    //find last space within length
    $last_space = strrpos(substr($input, 0, $length), ' ');
    $trimmed_text = substr($input, 0, $last_space);

    //add ellipses (...)
    if ($ellipses) {
        $trimmed_text .= '...';
    }

    return $trimmed_text;
}

/*     function cutText($text, $max_char, $mode = 2) {
        if ($mode == 1) {
            return substr($text, 0, $max_char);
        }

        //$char = $text{$max_char - 1};
        switch($mode)
        {
            case 2: 
                while($char != ' ') {
                    //$char = $text{--$max_char};
                }
            case 3:
                while($char != ' ') {
                    $char = $text{++$max_char};
                }
        }
        return substr($text, 0, $max_char);
    } */


function crypto_rand_secure($min, $max)
{
    $range = $max - $min;
    if ($range < 0) return $min;
    $log = log($range, 2);
    $bytes = (int) ($log / 8) + 1;
    $bits = (int) $log + 1;
    $filter = (int) (1 << $bits) - 1;
    do {
        $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
        $rnd = $rnd & $filter;
    } while ($rnd >= $range);
    return $min + $rnd;
}

function getToken($length)
{
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet .= "0123456789";
    for ($i = 0; $i < $length; $i++) {
        $token .= $codeAlphabet[crypto_rand_secure(0, strlen($codeAlphabet))];
    }
    return $token;
}


// CHECK ARRAYS
class checkArray
{

    function all($array, $predicate)
    {
        return array_filter($array, $predicate) === $array;
    }

    function any($array, $predicate)
    {
        return array_filter($array, $predicate) !== array();
    }
}
