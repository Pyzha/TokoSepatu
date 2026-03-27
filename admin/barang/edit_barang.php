<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../config/koneksi.php';

$id = $_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM barang WHERE id='$id'");
$d = mysqli_fetch_array($data);
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Barang</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { background:#f4f6f9; }
.card-box {
    background:white;
    padding:25px;
    border-radius:10px;
    box-shadow:0 4px 10px rgba(0,0,0,0.05);
}
</style>

</head>

<body>

<div class="container mt-5">

<div class="card-box col-md-6 mx-auto">

<h4 class="mb-3">Edit Barang</h4>

<form action="update_barang.php" method="POST">

<input type="hidden" name="id" value="<?= $d['id'] ?>">

<label>Nama Barang</label>
<input type="text" name="nama" value="<?= $d['nama_barang'] ?>" class="form-control mb-3" required>

<label>Harga</label>
<input type="number" name="harga" value="<?= $d['harga'] ?>" class="form-control mb-3" required>

<label>Stok</label>
<input type="number" name="stok" value="<?= $d['stok'] ?>" class="form-control mb-3" required>

<label>Kategori</label>
<input type="text" name="kategori" value="<?= $d['kategori'] ?>" class="form-control mb-3" required>

<button class="btn btn-primary">Update</button>
<a href="tampil_barang.php" class="btn btn-secondary">Kembali</a>

</form>

</div>

</div>

</body>
</html>