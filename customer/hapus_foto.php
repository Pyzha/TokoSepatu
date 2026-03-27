<?php
session_start();
include '../config/koneksi.php';

// Cek login
if (!isset($_SESSION['customer'])) {
    header("Location: login.php");
    exit;
}

$id_customer = $_SESSION['customer'];

// Ambil data customer
$query = mysqli_query($conn, "SELECT foto FROM customer WHERE id='$id_customer'");
$customer = mysqli_fetch_array($query);

if ($customer && $customer['foto'] && $customer['foto'] != 'default.jpg') {
    // Hapus file foto
    $foto_path = "../uploads/profiles/" . $customer['foto'];
    if (file_exists($foto_path)) {
        unlink($foto_path);
    }
    
    // Update database ke default
    mysqli_query($conn, "UPDATE customer SET foto='default.jpg' WHERE id='$id_customer'");
    
    echo "<script>alert('Foto profil berhasil dihapus');window.location='profile.php';</script>";
} else {
    header("Location: profile.php");
    exit;
}
?>