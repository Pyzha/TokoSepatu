<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/style.css">
</head>

<video autoplay muted loop playsinline class="video-bg">
    <source src="../video/bg.mp4" type="video/mp4">
</video>

<body class="bg-dark">

<div class="container d-flex justify-content-center align-items-center vh-100">
  <div class="card p-4 shadow" style="width:350px;">
    <h4 class="text-center">Admin Login</h4>

    <form action="login_proses.php" method="POST">
      <input type="text" name="username" class="form-control mb-2" placeholder="Username">
      <input type="password" name="password" class="form-control mb-3" placeholder="Password">
      <button class="btn btn-primary w-100">Login</button>
      <a href="lupa_password.php" class="d-block text-center mt-2">Lupa Password?</a>
    </form>

  </div>
</div>

</body>
</html>