<?php
include 'config.php';

/* ===============================
   SET WAKTU DASAR
================================ */
$tanggal_sekarang = date('Y-m-d');
$bulan_sekarang   = date('m');
$tahun_sekarang   = date('Y');
$pukul            = date('H:i');

/* ===============================
   HELPER FUNCTION (ANTI DOUBLE)
================================ */
if (!function_exists('getCount')) {
    function getCount($table, $where = '')
    {
        global $koneksi;

        $sql = "SELECT COUNT(*) AS total FROM $table";
        if (!empty($where)) {
            $sql .= " WHERE $where";
        }

        $query = mysqli_query($koneksi, $sql);
        if (!$query) return 0;

        $data = mysqli_fetch_assoc($query);
        return (int) ($data['total'] ?? 0);
    }
}

/* ===============================
   STATISTIK TASK
================================ */

// Total task
$totalTask = getCount('tasks', "id_user = '$sesi_id'");

// Task selesai
$totalSelesai = getCount(
    'tasks',
    "id_user = '$sesi_id' AND status = 'selesai'"
);

// Task belum selesai
$totalBelum = getCount(
    'tasks',
    "id_user = '$sesi_id' AND status = 'belum'"
);

// Deadline hari ini
$deadlineHariIni = getCount(
    'tasks',
    "id_user = '$sesi_id' 
     AND status = 'belum'
     AND DATE(deadline) = CURDATE()"
);

// Deadline terlewat
$deadlineLewat = getCount(
    'tasks',
    "id_user = '$sesi_id'
     AND status = 'belum'
     AND deadline < NOW()"
);
