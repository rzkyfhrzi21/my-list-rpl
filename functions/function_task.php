<?php
require_once 'config.php';

/*
|--------------------------------------------------------------------------
| TAMBAH TASK
|--------------------------------------------------------------------------
*/
if (isset($_POST['btn_add'])) {

    $id_user     = $sesi_id;
    $name        = trim($_POST['name']);
    $description = trim($_POST['description']);
    $category    = trim($_POST['category']);
    $priority    = trim($_POST['priority']);
    $deadline    = $_POST['deadline'] ?? null;
    $reminder    = $_POST['reminder'] ?? null;

    if ($name === '' || $priority === '') {
        header("Location: ../index.php?page=dashboard&action=add&result=error");
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
            created_at
        ) VALUES (
            '$id_user',
            '$name',
            '$description',
            '$category',
            '$priority',
            'belum',
            " . ($deadline ? "'$deadline'" : "NULL") . ",
            " . ($reminder ? "'$reminder'" : "NULL") . ",
            NOW()
        )
    ");

    header("Location: ../index.php?page=dashboard&action=add&result=success");
    exit;
}


/*
|--------------------------------------------------------------------------
| UPDATE TASK
|--------------------------------------------------------------------------
*/
if (isset($_POST['btn_edit'])) {

    $id_task     = $_POST['id'];
    $name        = trim($_POST['name']);
    $description = trim($_POST['description']);
    $category    = trim($_POST['category']);
    $priority    = trim($_POST['priority']);
    $deadline    = $_POST['deadline'] ?? null;
    $reminder    = $_POST['reminder'] ?? null;

    if (!$id_task || !$name) {
        header("Location: ../index.php?page=dashboard&action=update&result=error");
        exit;
    }

    mysqli_query($koneksi, "
        UPDATE tasks SET
            name        = '$name',
            description = '$description',
            category    = '$category',
            priority    = '$priority',
            deadline    = " . ($deadline ? "'$deadline'" : "NULL") . ",
            reminder    = " . ($reminder ? "'$reminder'" : "NULL") . ",
            updated_at  = NOW()
        WHERE id_task = '$id_task'
        AND id_user = '$sesi_id'
    ");

    header("Location: ../index.php?page=dashboard&action=update&result=success");
    exit;
}


/*
|--------------------------------------------------------------------------
| DELETE TASK
|--------------------------------------------------------------------------
*/
if (isset($_POST['btn_delete'])) {

    $id_task = $_POST['id'];

    if (!$id_task) {
        header("Location: ../index.php?page=dashboard&action=delete&result=error");
        exit;
    }

    mysqli_query($koneksi, "
        DELETE FROM tasks
        WHERE id_task = '$id_task'
        AND id_user = '$sesi_id'
    ");

    header("Location: ../index.php?page=dashboard&action=delete&result=success");
    exit;
}
