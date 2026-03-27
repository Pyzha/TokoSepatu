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

// Proses upload foto
if (isset($_POST['submit'])) {
    $target_dir = "../uploads/profiles/";
    
    // Buat folder jika belum ada
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file = $_FILES['foto'];
    $error = $file['error'];
    
    if ($error == 0) {
        $extensi = pathinfo($file['name'], PATHINFO_EXTENSION);
        $extensi_valid = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array(strtolower($extensi), $extensi_valid)) {
            if ($file['size'] <= 2 * 1024 * 1024) {
                $nama_baru = time() . '_' . $id_customer . '.' . $extensi;
                
                if (move_uploaded_file($file['tmp_name'], $target_dir . $nama_baru)) {
                    // Hapus foto lama jika bukan default
                    if ($customer['foto'] && $customer['foto'] != 'default.jpg' && file_exists($target_dir . $customer['foto'])) {
                        unlink($target_dir . $customer['foto']);
                    }
                    
                    mysqli_query($conn, "UPDATE customer SET foto='$nama_baru' WHERE id='$id_customer'");
                    echo "<script>alert('Foto profil berhasil diupdate!');window.location='profile.php';</script>";
                    exit;
                } else {
                    $error_msg = "Gagal mengupload file";
                }
            } else {
                $error_msg = "Ukuran file terlalu besar. Maksimal 2MB";
            }
        } else {
            $error_msg = "Format file tidak didukung. Gunakan JPG, JPEG, PNG, GIF, atau WEBP";
        }
    } else {
        $error_msg = "Pilih file terlebih dahulu";
    }
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
    <title>Edit Foto Profil - TokoSepatu</title>
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
        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 20px;
            display: block;
            border: 3px solid #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .preview-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-top: 10px;
            border: 2px solid #ddd;
        }
        .btn-upload {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            border: none;
        }
        .btn-upload:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79,70,229,0.3);
        }
    </style>
</head>
<body>

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

<div class="container mt-5">
    <div class="card-box">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">
                <i class="fas fa-camera"></i> Edit Foto Profil
            </h4>
            <a href="profile.php" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        
        <!-- Foto Saat Ini -->
        <img src="<?= $foto_path ?>?t=<?= time() ?>" alt="Foto Profil" class="profile-image" id="currentPhoto">
        
        <?php if (isset($error_msg)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> <?= $error_msg ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label fw-bold">Pilih Foto Baru</label>
                <input type="file" name="foto" class="form-control" accept="image/*" required onchange="previewImage(this)">
                <small class="text-muted">
                    <i class="fas fa-info-circle"></i> Format: JPG, JPEG, PNG, GIF, WEBP | Max: 2MB
                </small>
            </div>
            
            <div id="previewArea" class="text-center mb-3" style="display:none;">
                <p class="text-success"><i class="fas fa-image"></i> Preview Foto Baru:</p>
                <img id="preview" class="preview-img">
            </div>
            
            <button type="submit" name="submit" class="btn btn-upload text-white w-100">
                <i class="fas fa-upload"></i> Upload Foto
            </button>
        </form>
        
        <?php if ($customer['foto'] && $customer['foto'] != 'default.jpg'): ?>
            <div class="mt-3 text-center">
                <a href="hapus_foto.php" class="btn btn-outline-danger btn-sm" 
                   onclick="return confirm('Yakin ingin menghapus foto profil?')">
                    <i class="fas fa-trash"></i> Hapus Foto
                </a>
            </div>
        <?php endif; ?>
        
    </div>
</div>

<script>
function previewImage(input) {
    const previewArea = document.getElementById('previewArea');
    const preview = document.getElementById('preview');
    const currentPhoto = document.getElementById('currentPhoto');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewArea.style.display = 'block';
            currentPhoto.style.opacity = '0.5';
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        previewArea.style.display = 'none';
        currentPhoto.style.opacity = '1';
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>