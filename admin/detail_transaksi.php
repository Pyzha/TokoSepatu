<?php
include '../config/koneksi.php';

$id = $_GET['id'];

$data = mysqli_query($conn, "
SELECT dt.*, barang.nama_barang 
FROM detail_transaksi dt
JOIN barang ON dt.id_barang = barang.id
WHERE dt.id_transaksi='$id'
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Detail Transaksi</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">

<h3>Detail Transaksi</h3>

<table class="table">
<tr>
<th>Barang</th>
<th>Qty</th>
<th>Subtotal</th>
</tr>

<?php while($d=mysqli_fetch_array($data)){ ?>
<tr>
<td><?= $d['nama_barang'] ?></td>
<td><?= $d['qty'] ?></td>
<td><?= $d['subtotal'] ?></td>
</tr>
<?php } ?>

</table>

<a href="dashboard.php" class="btn btn-secondary">Kembali</a>

</body>
</html>