<?php
require_once 'config.php';
session_start();
ob_start();

$sesi_id   = $_SESSION['sesi_id'] ?? null;


/* ======================================================
   UPDATE DATA PROFIL (DATA UMUM)
====================================================== */
if (isset($_POST['btn_editdatapribadi'])) {

	$id_user	= $_POST['id_user'];
	$name		= trim(htmlspecialchars($_POST['name']));
	$email		= trim(htmlspecialchars($_POST['email']));

	mysqli_query($koneksi, "
        UPDATE users SET
            name = '$name',
            email = '$email',
            updated_at = NOW()
        WHERE id_user = '$id_user'
    ");

	header("Location: ../?page=profil&action=editprofil&result=success");
	exit;
}

/* ======================================================
   UPDATE DATA AKUN (USERNAME / PASSWORD / ROLE)
====================================================== */
if (isset($_POST['btn_editdataakun'])) {

	$id_user   = $_POST['id_user'];
	$password  = htmlspecialchars(trim($_POST['password']));
	$confirm   = htmlspecialchars(trim($_POST['konfirmasi_password']));


	if (!empty($password)) {
		if ($password !== $confirm) {
			header("Location: ../?page=profil&action=password_mismatch");
			exit;
		}
		$query = "
            UPDATE users SET 
                password = '$password',
                updated_at = NOW()
            WHERE id_user = '$id_user'
        ";
	}

	mysqli_query($koneksi, $query);

	header("Location: ../?page=profil&action=editprofil&result=success");
	exit;
}

/* ======================================================
   HAPUS AKUN
====================================================== */
if (isset($_POST['btn_deleteakun'])) {

	$id_user  = $_POST['id_user'];

	mysqli_query($koneksi, "DELETE FROM users WHERE id_user='$id_user'");

	if ($id_user === $sesi_id) {
		header("Location: ../auth/logout.php");
	} else {
		header("Location: ../?page=profil&action=deleteakun&result=success");
	}
	exit;
}

if (isset($_POST['btn_userregister'])) {

	$name     = trim($_POST['name'] ?? '');
	$email    = trim($_POST['email'] ?? '');
	$password = trim($_POST['password'] ?? '');
	$confirm  = trim($_POST['konfirmasi_password'] ?? '');

	// simpan input untuk refill form
	$_SESSION['form_data'] = [
		'name' => $name,
		'email' => $email
	];

	// ================= VALIDASI WAJIB =================
	if ($name === '' || $email === '' || $password === '' || $confirm === '') {
		header("Location: ../?page=registrasi&action=emptyregisterfield");
		exit;
	}

	// validasi format email
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		header("Location: ../?page=registrasi&action=invalidemail");
		exit;
	}

	// konfirmasi password
	if ($password !== $confirm) {
		header("Location: ../?page=registrasi&action=passwordnotsame");
		exit;
	}

	// cek email unik
	$cek_email = mysqli_query($koneksi, "SELECT id_user FROM users WHERE email='$email' LIMIT 1");
	if (mysqli_num_rows($cek_email) > 0) {
		header("Location: ../?page=registrasi&action=emailexist");
		exit;
	}

	// ================= INSERT DATA =================
	$query = "
        INSERT INTO users (
            name,
            email,
            password,
            created_at
        ) VALUES (
            '$name',
            '$email',
            '$password',
            NOW()
        )
    ";

	$insert = mysqli_query($koneksi, $query);

	if ($insert) {
		unset($_SESSION['form_data']);
		header("Location: ../?page=registrasi&action=adduser&result=success");
		exit;
	} else {
		header("Location: ../?page=registrasi&action=adduser&result=error");
		exit;
	}
}


ob_end_flush();
