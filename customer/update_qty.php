<?php
session_start();
include '../config/koneksi.php';

// Cek login
if (!isset($_SESSION['customer'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$aksi = $_GET['aksi'];

// Ambil data keranjang
$query = mysqli_query($conn, "SELECT * FROM keranjang WHERE id='$id'");
$keranjang = mysqli_fetch_array($query);

if (!$keranjang) {
    header("Location: keranjang.php");
    exit;
}

if ($aksi == 'plus') {
    // Tambah quantity
    mysqli_query($conn, "UPDATE keranjang SET qty = qty + 1 WHERE id='$id'");
} 
elseif ($aksi == 'minus') {
    // Kurangi quantity
    $qty_baru = $keranjang['qty'] - 1;
    
    if ($qty_baru <= 0) {
        // Jika quantity 0, hapus item dari keranjang
        mysqli_query($conn, "DELETE FROM keranjang WHERE id='$id'");
    } else {
        mysqli_query($conn, "UPDATE keranjang SET qty = qty - 1 WHERE id='$id'");
    }
}

header("Location: keranjang.php");
exit;
?>