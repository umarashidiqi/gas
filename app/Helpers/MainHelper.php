<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

function formatTanggal($timestamp = '', $date_format = 'l, j F Y | H:i', $suffix = '')
{
    if (trim($timestamp) == '') {
        $timestamp = time();
    } elseif (!ctype_digit($timestamp)) {
        $timestamp = strtotime($timestamp);
    }

    $date_format = preg_replace("/S/", "", $date_format);
    $pattern = array(
        '/Mon[^day]/', '/Tue[^sday]/', '/Wed[^nesday]/', '/Thu[^rsday]/',
        '/Fri[^day]/', '/Sat[^urday]/', '/Sun[^day]/', '/Monday/', '/Tuesday/',
        '/Wednesday/', '/Thursday/', '/Friday/', '/Saturday/', '/Sunday/',
        '/Jan[^uary]/', '/Feb[^ruary]/', '/Mar[^ch]/', '/Apr[^il]/', '/May/',
        '/Jun[^e]/', '/Jul[^y]/', '/Aug[^ust]/', '/Sep[^tember]/', '/Oct[^ober]/',
        '/Nov[^ember]/', '/Dec[^ember]/', '/January/', '/February/', '/March/',
        '/April/', '/June/', '/July/', '/August/', '/September/', '/October/',
        '/November/', '/December/',
    );
    $replace = array(
        'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min',
        'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu',
        'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des',
        'Januari', 'Februari', 'Maret', 'April', 'Juni', 'Juli', 'Agustus', 'September',
        'Oktober', 'November', 'Desember',
    );
    $date = date($date_format, $timestamp);
    $date = preg_replace($pattern, $replace, $date);
    $date = "{$date} {$suffix}";
    return $date;
}

function formatText($string, $delimiters = array(" ", "-", ".", "'", "O'", "Mc"), $exceptions = array("and", "to", "of", "das", "dos", "I", "II", "III", "IV", "V", "VI", "dan", "SiLPA", "dr", "Dr.", "RSUD", "RSJD", "RM", "SKPKD", "DPRD", "yang", "atau", "dari", "sah", "NKRI")) {
    $string = mb_convert_case($string, MB_CASE_TITLE, "UTF-8");
    foreach ($delimiters as $dlnr => $delimiter) {
        $words = explode($delimiter, $string);
        $newwords = array();
        foreach ($words as $wordnr => $word) {
            if (in_array(mb_strtoupper($word, "UTF-8"), $exceptions)) {
                $word = mb_strtoupper($word, "UTF-8");
            } elseif (in_array(mb_strtolower($word, "UTF-8"), $exceptions)) {
                $word = mb_strtolower($word, "UTF-8");
            } elseif (!in_array($word, $exceptions)) {
                $word = ucfirst($word);
            }
            array_push($newwords, $word);
        }
        $string = join($delimiter, $newwords);
    }
    return $string;
}

function formatAngka($value, $currency = false)
{
    if ($value != "") {
        $pecah = explode(" ", $value);
        $nilai = explode(".", $pecah[0]);
        $angka = $nilai[0];
        $koma = (int) @$nilai[1];
        if ($koma > 0) {
            $angka = number_format((string) $pecah[0], 2, ",", ".");
        } else {
            $angka = number_format((float) $angka, 0, ",", ".");
        }

        if ($currency == true) {
            $angka = 'Rp' . $angka . ',00';
        }
        return $angka;
    } else {
        return (string) $value;
    }
}

function subString($text, $start, $end)
{
    return Str::substr($text, $start, $end);
}

function permissionType($permission)
{
    $read   = Str::contains($permission, 'read');
    if($read) {
        return '<a href="#" class="badge badge-light-success fs-7 m-1">'. formatText($permission).'</a>';
    }
    $create = Str::contains($permission, 'create');
    if($create) {
        return '<a href="#" class="badge badge-light-primary fs-7 m-1">'. formatText($permission).'</a>';
    }
    $delete = Str::contains($permission, 'delete');
    if($delete) {
        return '<a href="#" class="badge badge-light-danger fs-7 m-1">'. formatText($permission).'</a>';
    }
    $update = Str::contains($permission, 'update');
    if($update) {
        return '<a href="#" class="badge badge-light-info fs-7 m-1">'. formatText($permission).'</a>';
    }
}

function textColor($status)
{
    if($status == 'Tercapai'){
        return 'success';
    }
    else{
        return 'danger';
    }
}
