<?php
require_once __DIR__ . '/../../config/koneksi.php';

$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM barang WHERE id='$id'");

header("Location: tampil_barang.php");
exit;
?>