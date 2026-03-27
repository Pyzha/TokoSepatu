<?php
include '../config/koneksi.php';

$user  = $_POST['username'];
$email = $_POST['email'];
$pass  = password_hash($_POST['password'], PASSWORD_DEFAULT);

$cek = mysqli_query($conn,"SELECT * FROM customer WHERE email='$email'");
if(mysqli_num_rows($cek)>0){
    echo "<script>alert('Email sudah digunakan');window.location='register.php';</script>";
    exit;
}

mysqli_query($conn,"INSERT INTO customer(username,email,password)
VALUES('$user','$email','$pass')");

header("Location: login.php");
?>