<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html>
<head>
<title>Tambah Barang</title>
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

<h4 class="mb-3">Tambah Barang</h4>

<form action="input_barang.php" method="POST" enctype="multipart/form-data">

<label>Nama Barang</label>
<input type="text" name="nama" class="form-control mb-3" required>

<label>Harga</label>
<input type="number" name="harga" class="form-control mb-3" required>

<label>Stok</label>
<input type="number" name="stok" class="form-control mb-3" required>

<label>Kategori</label>
<input type="text" name="kategori" class="form-control mb-3" required>

<label>Gambar</label>
<input type="file" name="gambar" class="form-control mb-3" required>

<button class="btn btn-success">Simpan</button>
<a href="tampil_barang.php" class="btn btn-secondary">Kembali</a>

</form>

</div>

</div>

</body>
</html>