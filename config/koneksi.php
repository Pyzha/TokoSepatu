<?php
$conn = mysqli_connect("localhost", "root", "", "TokoSepatu");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>