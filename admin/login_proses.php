<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';

$user = $_POST['username'];
$pass = $_POST['password'];

$query = mysqli_query($conn, "SELECT * FROM admin WHERE username='$user' AND password='$pass'");
$data = mysqli_fetch_array($query);

if($data){
    $_SESSION['admin'] = $data['id'];

    header("Location: dashboard.php");
    exit;
} else {
    echo "<script>alert('Login gagal');window.location='index.php';</script>";
}
?>