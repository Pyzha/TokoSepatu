<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>
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

        <h4 class="text-center mb-2">Reset Password</h4>
        <p class="text-center">Masukkan email untuk menerima link reset</p>

        <form action="kirim_reset.php" method="POST">
            <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
            <button type="submit" class="btn btn-dark w-100">Kirim Link Reset</button>
        </form>

        <div class="text-center mt-3">
            <a href="login.php">← Kembali ke Login</a>
        </div>

    </div>
</div>

</body>
</html>