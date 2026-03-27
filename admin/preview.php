<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';

$query = mysqli_query($conn, "SELECT * FROM barang");
?>

<!DOCTYPE html>
<html>
<head>
<title>Preview Toko</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { background:#f8f9fa; }
.card:hover { transform: translateY(-5px); transition:0.3s; }
.navbar { box-shadow:0 2px 10px rgba(0,0,0,0.1); }
.hero {
    background: linear-gradient(45deg,#111,#444);
    color:white;
    border-radius:10px;
}
</style>

</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-light bg-white px-4">
<span class="navbar-brand fw-bold">Preview TokoSepatu</span>

<a href="dashboard.php" class="btn btn-dark">← Dashboard</a>
</nav>

<!-- HERO -->
<div class="container mt-4">
<div class="hero p-5">
<h2>Preview Toko 👟</h2>
<p>Ini tampilan customer</p>
</div>
</div>

<!-- PRODUK -->
<div class="container mt-4">
<div class="row">

<?php while($d=mysqli_fetch_array($query)){ ?>

<div class="col-md-3">
<div class="card shadow-sm mb-4">

<img src="../gambar/<?= $d['gambar'] ?>" style="height:200px; object-fit:cover;">

<div class="card-body text-center">

<h6><?= $d['nama_barang'] ?></h6>
<h5 class="text-primary">Rp <?= number_format($d['harga']) ?></h5>
<p class="text-muted">Stok: <?= $d['stok'] ?></p>

<?php if($d['stok'] > 0){ ?>
<button class="btn btn-dark w-100">+ Keranjang</button>
<?php } else { ?>
<button class="btn btn-secondary w-100" disabled>Stok Habis</button>
<?php } ?>

</div>

</div>
</div>

<?php } ?>

</div>
</div>

</body>
</html>