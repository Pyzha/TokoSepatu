<?php
require_once __DIR__ . '/../../config/koneksi.php';

$nama  = $_POST['nama'];
$harga = $_POST['harga'];
$stok  = $_POST['stok'];
$kategori  = $_POST['kategori'];

$gambar = $_FILES['gambar']['name'];
$tmp    = $_FILES['gambar']['tmp_name'];

move_uploaded_file($tmp, "../../gambar/".$gambar);

mysqli_query($conn, "INSERT INTO barang (nama_barang,harga,gambar,stok,kategori)
VALUES ('$nama','$harga','$gambar','$stok','$kategori')");

header("Location: tampil_barang.php");
exit;
?>