<?php
require_once __DIR__ . '/../../config/koneksi.php';

$id    = $_POST['id'];
$nama  = $_POST['nama'];
$harga = $_POST['harga'];
$stok  = $_POST['stok'];
$kategori  = $_POST['kategori'];

mysqli_query($conn, "UPDATE barang SET 
nama_barang='$nama',
harga='$harga',
stok='$stok',
kategori='$kategori'
WHERE id='$id'");

header("Location: tampil_barang.php");
exit;
?>