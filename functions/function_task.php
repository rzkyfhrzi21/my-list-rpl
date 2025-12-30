<?php
require_once 'config.php';

/* ======================================================
   TAMBAH TASK
====================================================== */
if (isset($_POST['btn_tambah'])) {

    $id_user     = $sesi_id;
    $name        = trim($_POST['name']);
    $description = trim($_POST['description']);
    $category    = trim($_POST['category']);
    $priority    = trim($_POST['priority']);
    $deadline    = $_POST['deadline'];
    $reminder    = $_POST['reminder'];

    // default status saat buat task
    $status = 'belum';

    if ($name == '' || $priority == '') {
        header("Location: ../dashboard/{$sesi_role}?page=task&status=empty");
        exit;
    }

    mysqli_query($koneksi, "
        INSERT INTO tasks (
            id_user,
            name,
            description,
            category,
            priority,
            status,
            deadline,
            reminder,
            dibuat_pada
        ) VALUES (
            '$id_user',
            '$name',
            '$description',
            '$category',
            '$priority',
            '$status',
            '$deadline',
            '$reminder',
            NOW()
        )
    ");

    header("Location: ../dashboard/{$sesi_role}?page=task&status=success");
    exit;
}

/* ======================================================
   UPDATE TASK
====================================================== */
if (isset($_POST['btn_edit'])) {

    $id_task     = $_POST['id'];
    $name        = trim($_POST['name']);
    $description = trim($_POST['description']);
    $category    = trim($_POST['category']);
    $priority    = trim($_POST['priority']);
    $deadline    = $_POST['deadline'];
    $reminder    = $_POST['reminder'];

    if ($id_task == '' || $name == '') {
        header("Location: ../dashboard/{$sesi_role}?page=task&status=invalid");
        exit;
    }

    mysqli_query($koneksi, "
        UPDATE tasks SET
            name = '$name',
            description = '$description',
            category = '$category',
            priority = '$priority',
            deadline = '$deadline',
            reminder = '$reminder',
            diubah_pada = NOW()
        WHERE id_task = '$id_task'
          AND id_user = '$sesi_id'
    ");

    header("Location: ../dashboard/{$sesi_role}?page=task&status=updated");
    exit;
}

/* ======================================================
   HAPUS TASK
====================================================== */
if (isset($_POST['btn_delete'])) {

    $id_task = $_POST['id'];

    mysqli_query($koneksi, "
        DELETE FROM tasks 
        WHERE id_task = '$id_task' 
        AND id_user = '$sesi_id'
    ");

    header("Location: ../dashboard/{$sesi_role}?page=task&status=deleted");
    exit;
}

/* ======================================================
   UPDATE STATUS TASK (BELUM ⇄ SELESAI)
   dipakai kalau nanti checkbox diaktifkan
====================================================== */
if (isset($_POST['btn_toggle_status'])) {

    $id_task = $_POST['id'];
    $status  = $_POST['status']; // belum / selesai

    if (!in_array($status, ['belum', 'selesai'])) {
        header("Location: ../dashboard/{$sesi_role}?page=task&status=invalid");
        exit;
    }

    mysqli_query($koneksi, "
        UPDATE tasks 
        SET status = '$status', diubah_pada = NOW()
        WHERE id_task = '$id_task'
        AND id_user = '$sesi_id'
    ");

    header("Location: ../dashboard/{$sesi_role}?page=task&status=updated");
    exit;
}
