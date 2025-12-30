<?php
require_once 'config.php';

header('Content-Type: application/json');

// validasi session
if (!$sesi_id) {
    echo json_encode([
        'success' => false,
        'message' => 'Session habis'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

if (($_POST['action'] ?? '') !== 'toggle_status') {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
    exit;
}

$id_task = (int) ($_POST['id'] ?? 0);
$status  = $_POST['status'] ?? '';

if (!$id_task || !in_array($status, ['belum', 'selesai'])) {
    echo json_encode(['success' => false, 'message' => 'Data tidak valid']);
    exit;
}

$sql = "
    UPDATE tasks
    SET status = '$status', updated_at = NOW()
    WHERE id_task = '$id_task'
      AND id_user = '$sesi_id'
";

$result = mysqli_query($koneksi, $sql);

if (!$result) {
    echo json_encode([
        'success' => false,
        'message' => mysqli_error($koneksi)
    ]);
    exit;
}

if (mysqli_affected_rows($koneksi) === 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Task tidak ditemukan / bukan milik user'
    ]);
    exit;
}

echo json_encode([
    'success' => true,
    'status' => $status
]);
exit;
