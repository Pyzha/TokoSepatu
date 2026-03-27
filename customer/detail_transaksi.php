<?php
session_start();
include '../config/koneksi.php';

// Cek login
if (!isset($_SESSION['customer'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$id_customer = $_SESSION['customer'];

// Ambil data transaksi (pastikan milik customer ini)
$transaksi = mysqli_query($conn, "
    SELECT * FROM transaksi 
    WHERE id='$id' AND id_customer='$id_customer'
");

if (mysqli_num_rows($transaksi) == 0) {
    header("Location: riwayat.php");
    exit;
}

$t = mysqli_fetch_array($transaksi);

// Ambil detail transaksi
$detail = mysqli_query($conn, "
    SELECT dt.*, barang.nama_barang, barang.gambar 
    FROM detail_transaksi dt
    JOIN barang ON dt.id_barang = barang.id
    WHERE dt.id_transaksi='$id'
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi #<?= $id ?> - TokoSepatu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f5f5f5; }
        .navbar { background: black; }
        .card-box {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        .badge-status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 600;
        }
        .badge-pending  { background: #fef9c3; color: #854d0e; }
        .badge-selesai  { background: #dcfce7; color: #166534; }
        .badge-diproses { background: #dbeafe; color: #1e40af; }
        .badge-batal    { background: #fee2e2; color: #991b1b; }
        .product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-dark px-4 d-flex justify-content-between">
    <a href="index.php" class="navbar-brand d-flex align-items-center">
        <img src="../gambar/sepatu.png" width="35" class="me-2">
        <span class="fw-bold fs-5">TokoSepatu</span>
    </a>
    <div>
        <a href="keranjang.php" class="btn btn-outline-light me-2">🛒</a>
        <a href="riwayat.php" class="btn btn-outline-light me-2">📋</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</nav>

<div class="container mt-4 mb-5">
    <div class="card-box">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">🧾 Detail Transaksi #<?= $id ?></h4>
            <a href="riwayat.php" class="btn btn-outline-secondary">← Kembali</a>
        </div>

        <!-- Info Transaksi -->
        <div class="row mb-4">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <td width="120"><strong>Tanggal</strong></td>
                        <td>: <?= date('d M Y, H:i', strtotime($t['tanggal'])) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Status</strong></td>
                        <td>: 
                            <?php
                            $status = $t['status'];
                            $badgeClass = 'badge-pending';
                            if ($status == 'Selesai') $badgeClass = 'badge-selesai';
                            elseif ($status == 'Diproses') $badgeClass = 'badge-diproses';
                            elseif ($status == 'Batal') $badgeClass = 'badge-batal';
                            ?>
                            <span class="badge-status <?= $badgeClass ?>"><?= $status ?></span>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6 text-end">
                <h5 class="text-muted">Total Pembayaran</h5>
                <h2 class="text-success fw-bold">Rp <?= number_format($t['total'], 0, ',', '.') ?></h2>
            </div>
        </div>

        <hr>

        <!-- Detail Produk -->
        <h5 class="mb-3">📦 Produk yang Dibeli</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Produk</th>
                        <th>Nama Barang</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                $total = 0;
                while($d = mysqli_fetch_array($detail)): 
                ?>
                    <tr>
                        <td width="80">
                            <img src="../gambar/<?= $d['gambar'] ?>" class="product-img">
                        </td>
                        <td><?= htmlspecialchars($d['nama_barang']) ?></td>
                        <td><?= $d['qty'] ?></td>
                        <td>Rp <?= number_format($d['subtotal'] / $d['qty'], 0, ',', '.') ?></td>
                        <td class="fw-bold">Rp <?= number_format($d['subtotal'], 0, ',', '.') ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="4" class="text-end fw-bold">TOTAL</td>
                        <td class="fw-bold text-success">Rp <?= number_format($t['total'], 0, ',', '.') ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Tombol Aksi -->
        <div class="mt-4">
            <a href="index.php" class="btn btn-dark">🛍️ Belanja Lagi</a>
            
            <?php if ($t['status'] == 'Pending'): ?>
                <button class="btn btn-outline-warning ms-2" onclick="alert('Menunggu konfirmasi admin')">
                    ⏳ Menunggu Konfirmasi
                </button>
            <?php endif; ?>
        </div>

    </div>
</div>

</body>
</html>