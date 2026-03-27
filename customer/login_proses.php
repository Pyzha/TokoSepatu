<?php
session_start();
include '../config/koneksi.php';

$user = $_POST['username'];
$pass = $_POST['password'];

$q = mysqli_query($conn, "SELECT * FROM customer 
WHERE username='$user' OR email='$user'");

$d = mysqli_fetch_array($q);

if($d && password_verify($pass,$d['password'])){
    $_SESSION['customer'] = $d['id'];
    header("Location: index.php");
}else{
    echo "<script>alert('Login gagal');window.location='login.php';</script>";
}
?>