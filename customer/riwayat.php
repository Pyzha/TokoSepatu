<?php
session_start();
include '../config/koneksi.php';

// Cek login
if (!isset($_SESSION['customer'])) {
    header("Location: login.php");
    exit;
}

$id_customer = $_SESSION['customer'];

// Ambil data transaksi customer
$data = mysqli_query($conn, "
    SELECT * FROM transaksi 
    WHERE id_customer = '$id_customer'
    ORDER BY id DESC
");

// Cek apakah query berhasil
if (!$data) {
    die("Query Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi - TokoSepatu</title>
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
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
            cursor: pointer;
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
    <div class="card-box">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">📋 Riwayat Transaksi</h4>
            <a href="index.php" class="btn btn-outline-secondary">← Kembali Belanja</a>
        </div>

        <?php if (mysqli_num_rows($data) == 0): ?>
            <!-- Jika belum ada transaksi -->
            <div class="text-center py-5">
                <div style="font-size: 4rem;">🛒</div>
                <h5 class="mt-3 text-muted">Belum ada transaksi</h5>
                <p class="text-muted">Yuk mulai belanja sekarang!</p>
                <a href="index.php" class="btn btn-dark mt-2">Lihat Produk</a>
            </div>
        <?php else: ?>
            <!-- Tabel transaksi -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID Transaksi</th>
                            <th>Total</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while($d = mysqli_fetch_array($data)): 
                        // Tentukan class badge sesuai status
                        $status = $d['status'];
                        $badgeClass = 'badge-pending';
                        if ($status == 'Selesai') $badgeClass = 'badge-selesai';
                        elseif ($status == 'Diproses') $badgeClass = 'badge-diproses';
                        elseif ($status == 'Batal') $badgeClass = 'badge-batal';
                    ?>
                        <tr>
                            <td>
                                <strong>#<?= $d['id'] ?></strong>
                            </td>
                            <td>
                                Rp <?= number_format($d['total'], 0, ',', '.') ?>
                            </td>
                            <td>
                                <?= isset($d['tanggal']) ? date('d M Y, H:i', strtotime($d['tanggal'])) : '-' ?>
                            </td>
                            <td>
                                <span class="badge-status <?= $badgeClass ?>"><?= $status ?></span>
                            </td>
                            <td>
                                <a href="detail_transaksi.php?id=<?= $d['id'] ?>" 
                                   class="btn btn-sm btn-outline-primary">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        
    </div>
</div>

</body>
</html>