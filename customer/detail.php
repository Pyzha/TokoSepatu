<?php
session_start();
include '../config/koneksi.php';

// Cek login
if (!isset($_SESSION['customer'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];

// Ambil data barang
$data = mysqli_query($conn, "SELECT * FROM barang WHERE id='$id'");
$barang = mysqli_fetch_array($data);

if (!$barang) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $barang['nama_barang'] ?> - TokoSepatu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f0f2f5; }
        .navbar { background: black; }

        .detail-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .detail-img {
            width: 100%;
            height: 380px;
            object-fit: cover;
            background: #f5f5f5;
        }

        .detail-body {
            padding: 32px;
        }

        .badge-kategori {
            background: #f0ebff;
            color: #6d28d9;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 5px 12px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 12px;
        }

        .nama-barang {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 8px;
        }

        .harga-barang {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 16px;
        }

        .info-stok {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 5px 12px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 24px;
        }

        .info-stok.habis {
            background: #fef2f2;
            color: #dc2626;
            border-color: #fecaca;
        }

        .divider {
            height: 1px;
            background: #f0f0f0;
            margin: 20px 0;
        }

        .btn-tambah {
            background: linear-gradient(135deg, #16a34a, #15803d);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 14px 24px;
            font-size: 1rem;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s;
            text-decoration: none;
            display: block;
            text-align: center;
            margin-bottom: 10px;
        }

        .btn-tambah:hover {
            background: linear-gradient(135deg, #15803d, #166534);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(22,163,74,0.3);
            color: white;
        }

        .btn-kembali {
            background: #f5f5f5;
            color: #444;
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            font-size: 0.95rem;
            font-weight: 500;
            width: 100%;
            transition: all 0.2s;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .btn-kembali:hover {
            background: #e8e8e8;
            color: #222;
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
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</nav>

<div class="container mt-4 mb-5">

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Produk</a></li>
            <li class="breadcrumb-item active"><?= htmlspecialchars($barang['nama_barang']) ?></li>
        </ol>
    </nav>

    <div class="detail-card">
        <div class="row g-0">

            <!-- Gambar -->
            <div class="col-md-5">
                <img src="../gambar/<?= $barang['gambar'] ?>"
                     class="detail-img"
                     alt="<?= htmlspecialchars($barang['nama_barang']) ?>">
            </div>

            <!-- Info -->
            <div class="col-md-7">
                <div class="detail-body">

                    <span class="badge-kategori"><?= $barang['kategori'] ?></span>

                    <h1 class="nama-barang"><?= htmlspecialchars($barang['nama_barang']) ?></h1>

                    <div class="harga-barang">
                        Rp <?= number_format($barang['harga'], 0, ',', '.') ?>
                    </div>

                    <!-- Stok -->
                    <?php if ($barang['stok'] > 0): ?>
                        <div class="info-stok">
                            ✓ Stok tersedia: <?= $barang['stok'] ?> pasang
                        </div>
                    <?php else: ?>
                        <div class="info-stok habis">
                            ✕ Stok habis
                        </div>
                    <?php endif; ?>

                    <div class="divider"></div>

                    <!-- Tombol -->
                    <?php if ($barang['stok'] > 0): ?>
                        <a href="tambah_keranjang.php?id=<?= $barang['id'] ?>" class="btn-tambah">
                            🛒 Tambah ke Keranjang
                        </a>
                    <?php else: ?>
                        <button class="btn-tambah" style="opacity:0.5;cursor:not-allowed;" disabled>
                            Stok Habis
                        </button>
                    <?php endif; ?>

                    <a href="index.php" class="btn-kembali">← Kembali ke Produk</a>

                </div>
            </div>

        </div>
    </div>

</div>

</body>
</html>