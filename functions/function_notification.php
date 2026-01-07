<?php
session_start();
require_once 'config.php'; // pastikan $koneksi ada

// =========================
// CEK LOGIN
// =========================
if (!isset($_SESSION['sesi_id'])) {
    header("Location: ../index.php?error=login");
    exit;
}

$sesi_id = $_SESSION['sesi_id'];

// =========================
// TAMBAH NOTIFIKASI
// =========================
if (isset($_POST['btn_add_notif'])) {

    $task_id = $_POST['task_id'] ?? '';
    $title   = trim($_POST['title'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($task_id) || empty($title) || empty($message)) {
        header("Location: ../index.php?page=notification&result=error");
        exit;
    }

    mysqli_query(
        $koneksi,
        "INSERT INTO notifications
            (user_id, task_id, title, message, is_read, created_at)
         VALUES
            ('$sesi_id', '$task_id', '$title', '$message', 0, NOW())"
    );

    header("Location: ../index.php?page=notification&result=success_add");
    exit;
}

// =========================
// EDIT NOTIFIKASI
// =========================
if (isset($_POST['btn_edit_notif'])) {

    $notif_id = $_POST['notif_id'] ?? '';
    $title    = trim($_POST['title'] ?? '');
    $message  = trim($_POST['message'] ?? '');
    $is_read  = isset($_POST['is_read']) ? (int)$_POST['is_read'] : 0;

    if (empty($notif_id) || empty($title) || empty($message)) {
        header("Location: ../index.php?page=notification&result=error");
        exit;
    }

    mysqli_query(
        $koneksi,
        "UPDATE notifications SET
            title      = '$title',
            message    = '$message',
            is_read    = '$is_read',
            updated_at = NOW()
         WHERE id = '$notif_id'
           AND user_id = '$sesi_id'"
    );

    header("Location: ../index.php?page=notification&result=success_edit");
    exit;
}

// =========================
// HAPUS NOTIFIKASI
// =========================
if (isset($_POST['btn_delete_notif'])) {

    $notif_id = $_POST['notif_id'] ?? '';

    if (empty($notif_id)) {
        header("Location: ../index.php?page=notification&result=error");
        exit;
    }

    mysqli_query(
        $koneksi,
        "DELETE FROM notifications
         WHERE id = '$notif_id'
           AND user_id = '$sesi_id'"
    );

    header("Location: ../index.php?page=notification&result=success_delete");
    exit;
}

// =========================
// TANDAI SUDAH DIBACA
// =========================
if (isset($_POST['btn_read'])) {

    $notif_id = $_POST['notif_id'] ?? '';

    if (empty($notif_id)) {
        header("Location: ../index.php?page=notification&result=error");
        exit;
    }

    mysqli_query(
        $koneksi,
        "UPDATE notifications SET
            is_read    = 1,
            updated_at = NOW()
         WHERE id = '$notif_id'
           AND user_id = '$sesi_id'"
    );

    header("Location: ../index.php?page=notification&result=success_read");
    exit;
}

// =========================
// JIKA AKSES LANGSUNG
// =========================
header("Location: ../index.php?page=notification");
exit;
