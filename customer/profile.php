<?php
session_start();
include '../config/koneksi.php';

// Cek login
if (!isset($_SESSION['customer'])) {
    header("Location: login.php");
    exit;
}

$id_customer = $_SESSION['customer'];

// Ambil data customer
$query = mysqli_query($conn, "SELECT * FROM customer WHERE id='$id_customer'");
$customer = mysqli_fetch_array($query);

if (!$customer) {
    header("Location: logout.php");
    exit;
}

// Tentukan foto profil
$foto_profil = isset($customer['foto']) && $customer['foto'] ? $customer['foto'] : 'default.jpg';
$foto_path = "../uploads/profiles/" . $foto_profil;
if (!file_exists($foto_path)) {
    $foto_path = "../uploads/profiles/default.jpg";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - TokoSepatu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f5f5f5; }
        .navbar { background: black; }
        .card-box {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            max-width: 500px;
            margin: 0 auto;
        }
        .profile-image-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .info-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 4px;
        }
        .info-value {
            font-size: 1.1rem;
            font-weight: 500;
            color: #1f2937;
            margin-bottom: 20px;
        }
        .btn-edit {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            border: none;
            transition: all 0.3s;
        }
        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79,70,229,0.3);
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
        
        <!-- Foto Profil -->
        <div class="profile-image-container">
            <img src="<?= $foto_path ?>?t=<?= time() ?>" 
                 alt="Foto Profil" 
                 class="profile-image">
            <a href="edit_profile.php" class="btn btn-sm btn-outline-secondary mt-2">
                <i class="fas fa-camera"></i> Ganti Foto
            </a>
        </div>
        
        <h4 class="text-center fw-bold mb-4">Profil Saya</h4>
        
        <div class="mb-3">
            <div class="info-label">Username</div>
            <div class="info-value"><?= htmlspecialchars($customer['username']) ?></div>
        </div>
        
        <div class="mb-3">
            <div class="info-label">Email</div>
            <div class="info-value"><?= htmlspecialchars($customer['email']) ?></div>
        </div>
        
        <div class="mb-4">
            <div class="info-label">Member Sejak</div>
            <div class="info-value">
                <?= isset($customer['created_at']) ? date('d F Y', strtotime($customer['created_at'])) : '-' ?>
            </div>
        </div>
        
        <hr>
        
        <div class="d-flex gap-2 mt-3">
            <a href="index.php" class="btn btn-outline-secondary flex-grow-1">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <a href="edit_profile.php" class="btn btn-edit text-white flex-grow-1">
                <i class="fas fa-edit"></i> Edit Foto
            </a>
        </div>
        
    </div>
</div>

</body>
</html>