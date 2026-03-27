<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';

if(!isset($_SESSION['admin'])){
    header("Location: index.php");
    exit;
}

$total_barang    = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM barang"));
$total_transaksi = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM transaksi"));
$total_selesai   = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM transaksi WHERE status='Selesai'"));

$chart = mysqli_query($conn, "
    SELECT DATE(tanggal) as tgl, COUNT(*) as jumlah 
    FROM transaksi 
    GROUP BY DATE(tanggal)
    ORDER BY tgl ASC
");

$tgl = []; $jml = [];
while($c = mysqli_fetch_array($chart)){
    $tgl[] = $c['tgl'];
    $jml[] = $c['jumlah'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
body { background: #f1f5f9; }

/* SIDEBAR */
.sidebar {
    width: 240px;
    height: 100vh;
    background: #111827;
    color: white;
    position: fixed;
    top: 0; left: 0;
    display: flex;
    flex-direction: column;
}
.sidebar-brand {
    padding: 22px 20px;
    font-size: 1.1rem;
    font-weight: 700;
    border-bottom: 1px solid rgba(255,255,255,0.08);
}
.sidebar a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 13px 20px;
    color: #94a3b8;
    text-decoration: none;
    font-size: 0.9rem;
    transition: all 0.2s;
    border-left: 3px solid transparent;
}
.sidebar a:hover {
    background: #1f2937;
    color: white;
    border-left-color: #6366f1;
}
.sidebar a.active {
    background: #1f2937;
    color: white;
    border-left-color: #6366f1;
}
.sidebar .logout {
    margin-top: auto;
    border-top: 1px solid rgba(255,255,255,0.08);
}
.sidebar .logout a { color: #f87171; }
.sidebar .logout a:hover { background: rgba(239,68,68,0.1); border-left-color: #ef4444; }

/* CONTENT */
.content { margin-left: 240px; min-height: 100vh; }

/* TOPBAR */
.topbar {
    background: white;
    padding: 14px 28px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: sticky;
    top: 0;
    z-index: 100;
}
.topbar h5 { margin: 0; font-weight: 700; color: #1e293b; }
.admin-badge {
    background: #f1f5f9;
    color: #475569;
    padding: 5px 14px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

/* CARD STAT */
.card-box {
    background: white;
    padding: 22px;
    border-radius: 14px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    transition: transform 0.25s, box-shadow 0.25s;
}
.card-box:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.09);
}
.card-box .label {
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #94a3b8;
    margin-bottom: 8px;
}
.card-box .angka {
    font-size: 2rem;
    font-weight: 800;
    color: #1e293b;
    line-height: 1;
}
.card-box .icon {
    font-size: 1.8rem;
    opacity: 0.15;
    position: absolute;
    right: 20px;
    top: 20px;
}

/* STATUS BADGE */
.badge-status {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.78rem;
    font-weight: 600;
}
.badge-pending  { background: #fef9c3; color: #854d0e; }
.badge-selesai  { background: #dcfce7; color: #166534; }
.badge-proses   { background: #dbeafe; color: #1e40af; }
.badge-batal    { background: #fee2e2; color: #991b1b; }
</style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="sidebar-brand">🥿 TokoSepatu</div>
    <a href="dashboard.php" class="active">📊 Dashboard</a>
    <a href="barang/tampil_barang.php">📦 Kelola Barang</a>
    <a href="preview.php">👁 Preview Toko</a>
    <div class="logout">
        <a href="logout.php">🚪 Logout</a>
    </div>
</div>

<!-- CONTENT -->
<div class="content">

    <!-- TOPBAR -->
    <div class="topbar">
        <h5>Dashboard</h5>
        <span class="admin-badge">👤 Admin</span>
    </div>

    <div class="container-fluid px-4 py-4">

        <!-- STATISTIK -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card-box position-relative">
                    <div class="icon">📦</div>
                    <div class="label">Total Barang</div>
                    <div class="angka"><?= $total_barang ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-box position-relative">
                    <div class="icon">🧾</div>
                    <div class="label">Total Transaksi</div>
                    <div class="angka"><?= $total_transaksi ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-box position-relative">
                    <div class="icon">✅</div>
                    <div class="label">Transaksi Selesai</div>
                    <div class="angka"><?= $total_selesai ?></div>
                </div>
            </div>
        </div>

        <!-- CHART -->
        <div class="card-box mb-4">
            <h6 class="fw-bold mb-3">📈 Grafik Transaksi Harian</h6>
            <canvas id="myChart" height="80"></canvas>
        </div>

        <!-- TABEL TRANSAKSI -->
        <div class="card-box">
            <h6 class="fw-bold mb-3">🧾 Transaksi Terbaru</h6>
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#ID</th>
                        <th>Total</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $data = mysqli_query($conn, "SELECT * FROM transaksi ORDER BY id DESC LIMIT 10");
                while($d = mysqli_fetch_array($data)){
                    // Tentukan class badge sesuai status
                    $status = $d['status'];
                    $badgeClass = 'badge-pending';
                    if ($status == 'Selesai') $badgeClass = 'badge-selesai';
                    elseif ($status == 'Diproses') $badgeClass = 'badge-proses';
                    elseif ($status == 'Batal') $badgeClass = 'badge-batal';
                ?>
                <tr>
                    <td><strong>#<?= $d['id'] ?></strong></td>
                    <td>Rp <?= number_format($d['total'], 0, ',', '.') ?></td>
                    <td><?= isset($d['tanggal']) ? date('d M Y', strtotime($d['tanggal'])) : '-' ?></td>
                    <td>
                        <span class="badge-status <?= $badgeClass ?>"><?= $status ?></span>
                    </td>
                    <td>
                        <a href="detail_transaksi.php?id=<?= $d['id'] ?>"
                           class="btn btn-sm btn-outline-primary me-1">Detail</a>

                        <?php if ($status != 'Selesai'): ?>
                        <a href="update_status.php?id=<?= $d['id'] ?>&status=Selesai"
                           class="btn btn-sm btn-success"
                           onclick="return confirm('Tandai transaksi #<?= $d['id'] ?> sebagai Selesai?')">
                           Selesai
                        </a>
                        <?php else: ?>
                        <span class="text-muted small">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<!-- CHART SCRIPT -->
<script>
new Chart(document.getElementById('myChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode($tgl) ?>,
        datasets: [{
            label: 'Jumlah Transaksi',
            data: <?= json_encode($jml) ?>,
            borderColor: '#6366f1',
            backgroundColor: 'rgba(99,102,241,0.08)',
            borderWidth: 2.5,
            tension: 0.4,
            pointBackgroundColor: '#6366f1',
            fill: true
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 } }
        }
    }
});
</script>

</body>
</html>