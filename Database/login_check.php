<?php 
// mengaktifkan session pada php
session_start();

// menghubungkan php dengan koneksi database
include 'dbconn.php';

// menangkap data yang dikirim dari form login
$username = $_POST['username'];
$password = $_POST['password'];

// menyeleksi data user dengan username dan password yang sesuai
$login = mysqli_query($conn,"select * from login where username='$username' and password='$password'");
// menghitung jumlah data yang ditemukan
$cek = mysqli_num_rows($login);

// cek apakah username dan password di temukan pada database
if($cek > 0){

	$data = mysqli_fetch_assoc($login);

	// cek jika user login sebagai admin
	if($data['role']=="admin"){
 
		// buat session login dan username
		$_SESSION['username'] = $username;
		$_SESSION['role'] = "admin";
		// alihkan ke halaman dashboard admin
		header("location:table.php");
 
	// cek jika user login sebagai reseller
	}else if($data['role']=="reseller"){
		// buat session login dan username
		$_SESSION['username'] = $username;
		$_SESSION['role'] = "reseller";
		// alihkan ke halaman dashboard reseller
		header("location:dashboard_reseller.php");
 
	// cek jika user login sebagai client
	}else if($data['role']=="client"){
		// buat session login dan username
		$_SESSION['username'] = $username;
		$_SESSION['role'] = "client";
		// alihkan ke halaman dashboard pengurus
		header("location:dashboard_client.php");

	}else{

		// alihkan ke halaman login kembali
		header("location:index.php");
	}

	
}else{
	header("location:index.php");
}



?>