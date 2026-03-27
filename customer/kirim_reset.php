<?php
include '../config/koneksi.php';

$email = $_POST['email'];

// cek email
$q = mysqli_query($conn, "SELECT * FROM customer WHERE email='$email'");
$d = mysqli_fetch_array($q);

if(!$d){
    echo "<script>alert('Email tidak ditemukan');window.location='lupa_password.php';</script>";
    exit;
}

// buat token unik
$token = md5(rand());

// simpan token
mysqli_query($conn, "UPDATE customer SET reset_token='$token' WHERE email='$email'");

// link reset
$link = "http://localhost/TokoSepatu/customer/reset_password.php?token=$token";

// SIMULASI EMAIL (sementara)
echo "<h3>Link reset password:</h3>";
echo "<a href='$link'>$link</a>";
?>