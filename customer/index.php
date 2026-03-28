<?php
session_start();
include '../config/koneksi.php';

// ambil kategori unik
$kategori_query = mysqli_query($conn, "SELECT DISTINCT kategori FROM barang");

// ambil filter
$where = [];

if(isset($_GET['cari'])){
    $cari = $_GET['cari'];
    $where[] = "nama_barang LIKE '%$cari%'";
}

if(isset($_GET['kategori'])){
    $kategori = $_GET['kategori'];
    $where[] = "kategori='$kategori'";
}

if(isset($_GET['min']) && isset($_GET['max'])){
    $min = $_GET['min'];
    $max = $_GET['max'];
    if($min != "" && $max != ""){
        $where[] = "harga BETWEEN $min AND $max";
    }
}

$sql = "SELECT * FROM barang";
if(count($where) > 0){
    $sql .= " WHERE " . implode(" AND ", $where);
}

$data = mysqli_query($conn, $sql);

// kategori aktif
$kategori_aktif = isset($_GET['kategori']) ? $_GET['kategori'] : '';

// Ambil data customer untuk ditampilkan di profile
$nama_customer = '';
$foto_customer = 'default.jpg';
if(isset($_SESSION['customer'])){
    $id_customer = $_SESSION['customer'];
    $query_customer = mysqli_query($conn, "SELECT username, foto FROM customer WHERE id='$id_customer'");
    $customer = mysqli_fetch_array($query_customer);
    $nama_customer = $customer['username'];
    $foto_customer = $customer['foto'] ?? 'default.jpg';
    
    // Cek apakah file foto ada
    $foto_path = "../uploads/profiles/" . $foto_customer;
    if (!file_exists($foto_path) || $foto_customer == 'default.jpg') {
        $foto_customer = 'default.jpg';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<title>TokoSepatu</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
body { background: #f0f2f5; }

.navbar { background: black; }

/* ══════════════════════════════
   SIDEBAR
══════════════════════════════ */
.sidebar {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    position: sticky;
    top: 20px;
}

/* Header tiap seksi */
.sidebar-section-title {
    background: #1a1a2e;
    color: white;
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    padding: 10px 18px;
    margin: 0;
}

/* List kategori */
.kategori-list {
    list-style: none;
    padding: 8px 0;
    margin: 0;
}

.kategori-list li a {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 9px 18px;
    color: #444;
    text-decoration: none;
    font-size: 0.9rem;
    transition: background 0.2s, color 0.2s, padding-left 0.2s;
    border-left: 3px solid transparent;
}

.kategori-list li a:hover {
    background: #f5f0ff;
    color: #6d28d9;
    padding-left: 22px;
    border-left-color: #a78bfa;
}

.kategori-list li a.aktif {
    background: #f5f0ff;
    color: #6d28d9;
    font-weight: 600;
    border-left-color: #7c3aed;
}

.kategori-list li a::before {
    content: '›';
    font-size: 1rem;
    color: #ccc;
    transition: color 0.2s;
}

.kategori-list li a:hover::before,
.kategori-list li a.aktif::before {
    color: #7c3aed;
}

/* Divider antar seksi */
.sidebar-divider {
    height: 1px;
    background: #f0f0f0;
    margin: 0;
}

/* Filter harga */
.filter-body {
    padding: 14px 18px 18px;
}

.filter-body .form-control {
    border-radius: 8px;
    border: 1px solid #e0e0e0;
    font-size: 0.88rem;
    padding: 8px 12px;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.filter-body .form-control:focus {
    border-color: #a78bfa;
    box-shadow: 0 0 0 3px rgba(167,139,250,0.15);
}

.filter-body .form-control::placeholder {
    color: #bbb;
}

.btn-filter {
    background: linear-gradient(135deg, #1a1a2e, #2d2d5e);
    color: white;
    border: none;
    border-radius: 8px;
    padding: 9px;
    font-size: 0.88rem;
    font-weight: 600;
    letter-spacing: 0.04em;
    width: 100%;
    transition: all 0.25s;
}

.btn-filter:hover {
    background: linear-gradient(135deg, #2d2d5e, #4c1d95);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    color: white;
}

/* Label Min Max */
.filter-label {
    font-size: 0.78rem;
    font-weight: 600;
    color: #888;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-bottom: 5px;
    display: block;
}

/* Input Rp */
.input-rp {
    display: flex;
    align-items: center;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.input-rp:focus-within {
    border-color: #a78bfa;
    box-shadow: 0 0 0 3px rgba(167,139,250,0.15);
}

.rp-prefix {
    background: #f5f5f5;
    color: #888;
    font-size: 0.82rem;
    font-weight: 600;
    padding: 8px 10px;
    border-right: 1px solid #e0e0e0;
    white-space: nowrap;
    user-select: none;
}

.input-rp .form-control {
    border: none !important;
    border-radius: 0 !important;
    box-shadow: none !important;
    padding: 8px 10px !important;
    font-size: 0.88rem;
}

.input-rp .form-control:focus {
    box-shadow: none !important;
    border: none !important;
}

/* ══════════════════════════════
   CARD PRODUK
══════════════════════════════ */
.card-produk {
    border: none;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.07);
    transition: transform 0.3s, box-shadow 0.3s;
}

.card-produk:hover {
    transform: translateY(-6px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.13);
}

.card-produk img {
    height: 200px;
    object-fit: cover;
}

/* ══════════════════════════════
   PROFILE DROPDOWN
══════════════════════════════ */
.profile-dropdown {
    position: relative;
    display: inline-block;
}

.profile-btn {
    background: transparent;
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 40px;
    padding: 5px 12px 5px 8px;
    color: white;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
    cursor: pointer;
}

.profile-btn:hover {
    background: rgba(255,255,255,0.1);
    border-color: rgba(255,255,255,0.4);
}

.profile-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid white;
}

.profile-btn i {
    font-size: 1rem;
}

.dropdown-menu-custom {
    position: absolute;
    top: 100%;
    right: 0;
    margin-top: 8px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    min-width: 200px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.2s ease;
    z-index: 1000;
}

.profile-dropdown.active .dropdown-menu-custom {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.dropdown-menu-custom a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    color: #374151;
    text-decoration: none;
    transition: background 0.2s;
    font-size: 0.9rem;
}

.dropdown-menu-custom a:first-child {
    border-radius: 12px 12px 0 0;
}

.dropdown-menu-custom a:last-child {
    border-radius: 0 0 12px 12px;
}

.dropdown-menu-custom a:hover {
    background: #f3f4f6;
    color: #4f46e5;
}

.dropdown-menu-custom a i {
    width: 20px;
    color: #6b7280;
}

.dropdown-menu-custom a:hover i {
    color: #4f46e5;
}

.dropdown-divider {
    height: 1px;
    background: #e5e7eb;
    margin: 4px 0;
}

.dropdown-menu-custom .logout-item {
    color: #dc2626;
}

.dropdown-menu-custom .logout-item:hover {
    background: #fef2f2;
    color: #dc2626;
}

.dropdown-menu-custom .logout-item:hover i {
    color: #dc2626;
}
.btn-outline-light {
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-outline-light i, 
.btn-outline-light span {
    display: inline-block;
}
</style>

</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-dark px-4 d-flex justify-content-between" style="background: black;">
    <a href="index.php" class="navbar-brand d-flex align-items-center">
        <span style="font-size: 1.8rem;">👟</span>
        <span class="fw-bold fs-5 ms-2">TokoSepatu</span>
    </a>

    <form class="d-flex" method="GET">
        <input class="form-control me-2" type="search" name="cari"
               placeholder="Cari sepatu..."
               value="<?= isset($_GET['cari']) ? htmlspecialchars($_GET['cari']) : '' ?>">
        <button class="btn btn-outline-light">Search</button>
    </form>

    <div class="d-flex align-items-center gap-2">
    <?php if(isset($_SESSION['customer'])){ 
        $id_customer = $_SESSION['customer'];
        $query_customer = mysqli_query($conn, "SELECT username, foto FROM customer WHERE id='$id_customer'");
        $customer_data = mysqli_fetch_array($query_customer);
        $nama_customer = $customer_data['username'];
        $foto_customer = $customer_data['foto'] ?? 'default.jpg';
        
        $cart_query = mysqli_query($conn, "SELECT SUM(qty) as total FROM keranjang WHERE id_customer='$id_customer'");
        $cart_data = mysqli_fetch_array($cart_query);
        $cart_count = $cart_data['total'] ?? 0;
    ?>
        <!-- ICON KERANJANG -->
        <a href="keranjang.php" class="btn btn-outline-light position-relative" style="display: inline-flex; align-items: center; justify-content: center; width: 42px; height: 42px; padding: 0; border-radius: 50%;">
            🛒
            <?php if($cart_count > 0): ?>
                <span class="badge bg-danger rounded-pill position-absolute" style="top: -4px; right: -4px; font-size: 0.65rem; padding: 2px 5px;">
                    <?= $cart_count ?>
                </span>
            <?php endif; ?>
        </a>

        <!-- Profile Dropdown -->
        <div class="profile-dropdown" id="profileDropdown">
            <button class="profile-btn" onclick="toggleDropdown()">
                <img src="../uploads/profiles/<?= $foto_customer ?>?t=<?= time() ?>" 
                     class="profile-avatar" 
                     alt="Avatar"
                     onerror="this.src='../uploads/profiles/default.jpg'">
                <?= htmlspecialchars($nama_customer) ?>
                <i class="fas fa-chevron-down" style="font-size: 0.7rem;"></i>
            </button>
            <div class="dropdown-menu-custom">
                <a href="riwayat.php">
                    <i class="fas fa-history"></i> Riwayat Transaksi
                </a>
                <a href="profile.php">
                    <i class="fas fa-user"></i> Profil Saya
                </a>
                <div class="dropdown-divider"></div>
                <a href="logout.php" class="logout-item">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    <?php } else { ?>
        <a href="login.php" class="btn btn-outline-light">Login</a>
    <?php } ?>
    </div>
</nav>

<div class="container mt-4">
<div class="row">

    <!-- ══ SIDEBAR ══ -->
    <div class="col-md-3">
        <div class="sidebar">

            <!-- Kategori -->
            <p class="sidebar-section-title">📂 KATEGORI</p>
            <ul class="kategori-list">
                <li>
                    <a href="index.php" class="<?= $kategori_aktif == '' ? 'aktif' : '' ?>">
                        Semua Produk
                    </a>
                </li>
                <?php while($k = mysqli_fetch_array($kategori_query)){ ?>
                <li>
                    <a href="?kategori=<?= $k['kategori'] ?>"
                       class="<?= $kategori_aktif == $k['kategori'] ? 'aktif' : '' ?>">
                        <?= $k['kategori'] ?>
                    </a>
                </li>
                <?php } ?>
            </ul>

            <div class="sidebar-divider"></div>

            <!-- Filter Harga -->
            <p class="sidebar-section-title">💰 FILTER HARGA</p>
            <div class="filter-body">
                <form method="GET" id="formFilter">
                    <?php if(isset($_GET['kategori'])): ?>
                        <input type="hidden" name="kategori" value="<?= htmlspecialchars($_GET['kategori']) ?>">
                    <?php endif; ?>

                    <!-- Hidden inputs yang dikirim ke server (angka murni) -->
                    <input type="hidden" name="min" id="min_val" value="<?= isset($_GET['min']) ? $_GET['min'] : '' ?>">
                    <input type="hidden" name="max" id="max_val" value="<?= isset($_GET['max']) ? $_GET['max'] : '' ?>">

                    <!-- Input tampilan (pakai Rp + titik) -->
                    <div class="mb-2">
                        <label class="filter-label">MIN</label>
                        <div class="input-rp">
                            <span class="rp-prefix">Rp</span>
                            <input type="text" id="min_display" class="form-control"
                                   placeholder="0"
                                   value="<?= isset($_GET['min']) && $_GET['min'] != '' ? number_format($_GET['min'], 0, ',', '.') : '' ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="filter-label">MAX</label>
                        <div class="input-rp">
                            <span class="rp-prefix">Rp</span>
                            <input type="text" id="max_display" class="form-control"
                                   placeholder="0"
                                   value="<?= isset($_GET['max']) && $_GET['max'] != '' ? number_format($_GET['max'], 0, ',', '.') : '' ?>">
                        </div>
                    </div>

                    <button type="submit" class="btn-filter">Filter</button>
                </form>
            </div>

        </div>
    </div>

    <!-- ══ PRODUK ══ -->
    <div class="col-md-9">
        <h4 class="mb-3 fw-bold">Produk</h4>
        <div class="row">
            <?php if(mysqli_num_rows($data) == 0): ?>
                <div class="col-12 text-center py-5">
                    <i class="fas fa-box-open" style="font-size: 3rem; color: #ccc;"></i>
                    <p class="mt-3 text-muted">Tidak ada produk ditemukan</p>
                </div>
            <?php else: ?>
                <?php while($d = mysqli_fetch_array($data)){ ?>
                <div class="col-md-4 mb-4">
                    <div class="card card-produk">
                        <img src="../gambar/<?= $d['gambar'] ?>" class="card-img-top" alt="<?= $d['nama_barang'] ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= $d['nama_barang'] ?></h5>
                            <p class="text-primary fw-bold mb-1">Rp <?= number_format($d['harga']) ?></p>
                            <p class="text-muted small mb-3">Stok: <?= $d['stok'] ?></p>
                            <div class="d-flex gap-2">
                                <a href="detail.php?id=<?= $d['id'] ?>" class="btn btn-dark flex-grow-1">Detail</a>
                                <a href="tambah_keranjang.php?id=<?= $d['id'] ?>" class="btn btn-success flex-grow-1">Tambah</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            <?php endif; ?>
        </div>
    </div>

</div>
</div>

<script>
// Toggle dropdown profile
function toggleDropdown() {
    document.getElementById('profileDropdown').classList.toggle('active');
}

// Tutup dropdown jika klik di luar
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('profileDropdown');
    if (dropdown && !dropdown.contains(event.target)) {
        dropdown.classList.remove('active');
    }
});

function formatRupiah(angka) {
    let bersih = angka.replace(/\D/g, '');
    if (bersih === '') return '';
    return parseInt(bersih).toLocaleString('id-ID');
}

function bukaAngka(str) {
    return str.replace(/\./g, '');
}

function attachFormat(displayId, hiddenId) {
    const display = document.getElementById(displayId);
    const hidden  = document.getElementById(hiddenId);

    if(display && hidden) {
        display.addEventListener('input', function () {
            const raw      = bukaAngka(this.value);
            this.value     = formatRupiah(this.value);
            hidden.value   = raw;
        });

        display.addEventListener('blur', function () {
            if (this.value === '') hidden.value = '';
        });
    }
}

attachFormat('min_display', 'min_val');
attachFormat('max_display', 'max_val');

const formFilter = document.getElementById('formFilter');
if(formFilter) {
    formFilter.addEventListener('submit', function () {
        const minRaw = bukaAngka(document.getElementById('min_display').value);
        const maxRaw = bukaAngka(document.getElementById('max_display').value);
        document.getElementById('min_val').value = minRaw;
        document.getElementById('max_val').value = maxRaw;
    });
}
</script>

</body>
</html>