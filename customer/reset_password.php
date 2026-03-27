<?php
include '../config/koneksi.php';

if(!isset($_GET['token'])){
    die("Token tidak ditemukan");
}

$token = $_GET['token'];

// cek token
$q = mysqli_query($conn, "SELECT * FROM customer WHERE reset_token='$token'");
$d = mysqli_fetch_array($q);

if(!$d){
    die("Token tidak valid");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Reset Password</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex justify-content-center align-items-center" style="height:100vh;">

<div class="card p-4 col-md-3">

<h4 class="text-center mb-3">Password Baru</h4>

<form method="POST">

<input type="password" name="pass" class="form-control mb-3" placeholder="Password baru" required>

<button class="btn btn-dark w-100">Reset</button>

</form>

</div>

</body>
</html>

<?php
if(isset($_POST['pass'])){
    $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);

    mysqli_query($conn, "UPDATE customer SET 
    password='$pass',
    reset_token=NULL
    WHERE reset_token='$token'");

    echo "<script>alert('Password berhasil diubah');window.location='login.php';</script>";
}
?>