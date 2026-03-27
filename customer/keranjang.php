<?php
session_start();
include '../config/koneksi.php';

// Cek login dulu — kalau belum login, redirect ke login
if (!isset($_SESSION['customer'])) {
    header("Location: login.php");
    exit;
}

$id_customer = $_SESSION['customer'];

$data = mysqli_query($conn, "
    SELECT keranjang.*, barang.nama_barang, barang.harga, barang.gambar
    FROM keranjang 
    JOIN barang ON keranjang.id_barang = barang.id
    WHERE keranjang.id_customer = '$id_customer'
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang - TokoSepatu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f5f5f5; }
        .navbar { background: black; }
        .card-keranjang { border-radius: 12px; overflow: hidden; }
        .qty-box { display: flex; align-items: center; gap: 10px; }
        .qty-box a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px; height: 30px;
            background: #1a1a2e;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-size: 1.1rem;
            line-height: 1;
        }
        .qty-box a:hover { background: #333; }
        .total-box {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        .img-keranjang {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
        }
        .empty-cart {
            text-align: center;
            padding: 60px 20px;
            color: #888;
        }
        .empty-cart .icon { font-size: 4rem; }
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
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</nav>

<div class="container mt-4">

    <h4 class="mb-4 fw-bold">🛒 Keranjang Belanja</h4>

    <?php
    $total = 0;
    $items = [];
    while ($d = mysqli_fetch_array($data)) {
        $items[] = $d;
        $total += $d['harga'] * $d['qty'];
    }
    ?>

    <?php if (count($items) == 0): ?>

        <!-- Keranjang kosong -->
        <div class="empty-cart">
            <div class="icon">🛒</div>
            <h5 class="mt-3">Keranjang masih kosong</h5>
            <p>Yuk tambahkan produk ke keranjang dulu!</p>
            <a href="index.php" class="btn btn-dark mt-2">Lihat Produk</a>
        </div>

    <?php else: ?>

    <div class="row">

        <!-- DAFTAR ITEM -->
        <div class="col-md-8">
            <?php foreach ($items as $d): ?>
            <div class="card card-keranjang mb-3 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">

                    <img src="../gambar/<?= $d['gambar'] ?>" class="img-keranjang">

                    <div class="flex-grow-1">
                        <h6 class="mb-1 fw-bold"><?= $d['nama_barang'] ?></h6>
                        <p class="mb-1 text-muted">Rp <?= number_format($d['harga']) ?></p>
                        <p class="mb-0 text-muted small">Subtotal: <strong>Rp <?= number_format($d['harga'] * $d['qty']) ?></strong></p>
                    </div>

                    <!-- QTY CONTROL -->
                    <div class="qty-box">
                        <a href="update_qty.php?id=<?= $d['id'] ?>&aksi=minus">−</a>
                        <span class="fw-bold fs-5"><?= $d['qty'] ?></span>
                        <a href="update_qty.php?id=<?= $d['id'] ?>&aksi=plus">+</a>
                    </div>

                    <!-- HAPUS -->
                    <a href="hapus_keranjang.php?id=<?= $d['id'] ?>" 
                       class="btn btn-sm btn-outline-danger ms-2"
                       onclick="return confirm('Hapus item ini?')">🗑</a>

                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- TOTAL & CHECKOUT -->
        <div class="col-md-4">
            <div class="total-box">
                <h5 class="fw-bold mb-3">Ringkasan Pesanan</h5>
                <div class="d-flex justify-content-between mb-2">
                    <span><?= count($items) ?> item</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between fw-bold fs-5 mb-3">
                    <span>Total</span>
                    <span>Rp <?= number_format($total) ?></span>
                </div>
                <a href="checkout.php" class="btn btn-dark w-100">Checkout →</a>
                <a href="index.php" class="btn btn-outline-secondary w-100 mt-2">← Lanjut Belanja</a>
            </div>
        </div>

    </div>

    <?php endif; ?>

</div>

</body>
</html>