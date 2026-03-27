<?php
include '../config/koneksi.php';

if(isset($_POST['submit'])){
    $user = $_POST['username'];
    $pass = $_POST['password'];

    mysqli_query($conn, "UPDATE admin SET password='$pass' WHERE username='$user'");

    echo "<script>alert('Password berhasil diubah');window.location='index.php'</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Lupa Password</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">
<div class="card p-4" style="width:350px;">

<h4 class="text-center">Reset Password</h4>

<form method="POST">
<input type="text" name="username" class="form-control mb-2" placeholder="Username">
<input type="password" name="password" class="form-control mb-3" placeholder="Password Baru">
<button name="submit" class="btn btn-primary w-100">Reset</button>
</form>

</div>
</div>

</body>
</html>