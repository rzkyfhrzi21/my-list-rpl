<?php
require_once 'env.php';

date_default_timezone_set('Asia/Jakarta');
$pukul = date('H:i A');

// Memeriksa apakah link adalah localhost
$host = $_SERVER['HTTP_HOST'];
if ($host === 'localhost' || strpos($host, '127.0.0.1') !== false) {
    // Untuk penggunaan xampp
    $server     = 'localhost';
    $username   = 'root';
    $password   = '';
    $database   = 'my-list';
} else {
    // Untuk penggunaan hosting
    $server     = '';
    $username   = '';
    $password   = '';
    $database   = '';
}

$koneksi    = mysqli_connect($server, $username, $password, $database);

if (!$koneksi) {
    die('Koneksi gagal: ' . mysqli_connect_error());
}

if (!function_exists('formatTanggalIndonesia')) {
    function formatTanggalIndonesia($tanggalInggris)
    {
        $namaHari = [
            'Sunday'    => 'Minggu',
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu'
        ];

        $namaBulan = [
            'January'   => 'Januari',
            'February'  => 'Februari',
            'March'     => 'Maret',
            'April'     => 'April',
            'May'       => 'Mei',
            'June'      => 'Juni',
            'July'      => 'Juli',
            'August'    => 'Agustus',
            'September' => 'September',
            'October'   => 'Oktober',
            'November'  => 'November',
            'December'  => 'Desember'
        ];

        $date = new DateTime($tanggalInggris);
        $hariInggris = $date->format('l');
        $bulanInggris = $date->format('F');

        $hariIndonesia = $namaHari[$hariInggris];
        $bulanIndonesia = $namaBulan[$bulanInggris];

        return $hariIndonesia . ', ' . $date->format('d') . ' ' . $bulanIndonesia . ' ' . $date->format('Y');
    }
}
