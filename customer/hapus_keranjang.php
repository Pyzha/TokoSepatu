<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['customer'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM keranjang WHERE id='$id'");

header("Location: keranjang.php");
exit;
?>