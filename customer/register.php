<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<video autoplay muted loop playsinline class="video-bg">
    <source src="../video/bg.mp4" type="video/mp4">
</video>

<div class="overlay"></div>

<div class="center-box">
    <div class="card">

        <h4 class="text-center mb-4">Register</h4>

        <form action="register_proses.php" method="POST">
            <input type="text" name="username" class="form-control mb-3" placeholder="Username" required>
            <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
            <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
            <button type="submit" class="btn btn-dark w-100">Daftar</button>
        </form>

        <div class="text-center mt-3">
            <a href="login.php">Sudah punya akun? Login</a>
        </div>

    </div>
</div>

</body>
</html>