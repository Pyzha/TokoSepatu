<?php
require_once __DIR__ . '/../../config/koneksi.php';
$data = mysqli_query($conn, "SELECT * FROM barang");
?>

<!DOCTYPE html>
<html>
<head>
<title>Barang</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-4">

<div class="d-flex justify-content-between mb-3">
<h3>Data Barang</h3>
<div>
<a href="../dashboard.php" class="btn btn-secondary">← Dashboard</a>
<a href="form_barang.php" class="btn btn-success">+ Tambah</a>
</div>
</div>

<table class="table table-bordered">
<tr>
<th>Nama</th>
<th>Harga</th>
<th>Stok</th>
<th>Kategori</th>
<th>Gambar</th>
<th>Aksi</th>
</tr>

<?php while($d=mysqli_fetch_array($data)){ ?>
<tr>
<td><?= $d['nama_barang'] ?></td>
<td><?= $d['harga'] ?></td>
<td><?= $d['stok'] ?></td>
<td><?= $d['kategori'] ?></td>
<td><img src="../../gambar/<?= $d['gambar'] ?>" width="80"></td>
<td>
<a href="edit_barang.php?id=<?= $d['id'] ?>">Edit</a> |
<a href="hapus_barang.php?id=<?= $d['id'] ?>">Hapus</a>
</td>
</tr>
<?php } ?>

</table>

</div>

</body>
</html>