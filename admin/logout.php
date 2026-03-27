<?php
session_start();

session_unset();
session_destroy();

// arahkan ke halaman utama (home)
header("Location: index.php");
exit;
?>