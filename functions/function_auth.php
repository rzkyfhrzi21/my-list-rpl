<?php

session_start();

require_once 'config.php';

if (isset($_POST['btn_login'])) {
	$email 		= htmlspecialchars($_POST['email']);
	$password 	= htmlspecialchars($_POST['password']);

	$sql_login 		= mysqli_query($koneksi, "SELECT * from users where email = '$email' and password = '$password'");
	$jumlah_user 	= mysqli_num_rows($sql_login);
	$data_user		= mysqli_fetch_array($sql_login);

	if ($jumlah_user > 0) {
		$_SESSION['sesi_id']		= $data_user['id_user'];
		$_SESSION['sesi_nama']		= $data_user['name'];
		$_SESSION['sesi_email']		= $data_user['email'];

		header('Location: ../index');
	} else {
		header("Location: ../auth/login?action=login&status=error");
	}
}

if (isset($_POST['btn_register'])) {
	$name          	= htmlspecialchars($_POST['name']);
	$email          		= htmlspecialchars($_POST['email']);
	$password               = htmlspecialchars($_POST['password']);
	$konfirmasi_password    = htmlspecialchars($_POST['konfirmasi_password']);

	$sql_login          = mysqli_query($koneksi, "SELECT * from users where email = '$email'");
	$jumlah_users       = mysqli_num_rows($sql_login);
	$data_users         = mysqli_fetch_array($sql_login);

	if ($password !== $konfirmasi_password) {
		header("Location: ../auth/register?action=passwordnotsame&status=warning&email=" . $email . '&name=' . $name);
	} else {
		if ($jumlah_users > 0) {
			header("Location: ../auth/register?action=userexist&status=warning&name=" . $name);
		} else {

			$query_daftar    = "INSERT into users 
                                    set email    = '$email',
                                        name   = '$name', 
                                        role       	= '$role', 
                                        password    = '$password'";
			$daftar         = mysqli_query($koneksi, $query_daftar);

			if ($daftar) {
				header("Location: ../auth/login?action=registered&status=success");
			} else {
				header("Location: ../auth/register");
			}
		}
	}
}
