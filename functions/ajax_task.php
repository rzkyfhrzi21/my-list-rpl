<?php
// functions/ajax_task.php
// AJAX handler untuk toggle status task (belum <-> selesai)

header('Content-Type: application/json');

require_once 'config.php';

// Pastikan request POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Validasi action
$action = $_POST['action'] ?? '';
if ($action !== 'toggle_status') {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
    exit;
}

// Ambil data
$id_task = $_POST['id'] ?? '';
$status  = $_POST['status'] ?? '';

// Validasi input dasar
if ($id_task === '' || !in_array($status, ['belum', 'selesai'], true)) {
    echo json_encode(['success' => false, 'message' => 'Invalid payload']);
    exit;
}

// Casting ID biar aman dari input aneh
$id_task = (int)$id_task;

// Update task (dibatasi id_user supaya user tidak bisa ubah task orang lain)
$update = mysqli_query($koneksi, "
    UPDATE tasks 
    SET status = '$status', diubah_pada = NOW()
    WHERE id_task = '$id_task'
      AND id_user = '$sesi_id'
");

if ($update && mysqli_affected_rows($koneksi) > 0) {
    echo json_encode(['success' => true, 'status' => $status]);
    exit;
}

// Kalau tidak ada row berubah: bisa karena id_task tidak ada / bukan milik user
echo json_encode(['success' => false, 'message' => 'Update failed']);
exit;
