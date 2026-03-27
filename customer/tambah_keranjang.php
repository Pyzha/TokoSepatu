<?php
session_start();
include '../config/koneksi.php';

// Cek login
if (!isset($_SESSION['customer'])) {
    header("Location: login.php");
    exit;
}

$id_barang = $_GET['id'];
$id_customer = $_SESSION['customer'];

// Ambil stok barang
$query_stok = mysqli_query($conn, "SELECT stok, nama_barang FROM barang WHERE id='$id_barang'");
$barang = mysqli_fetch_array($query_stok);

if(!$barang){
    header("Location: index.php");
    exit;
}

// Cek apakah barang sudah di keranjang
$cek = mysqli_query($conn, "SELECT * FROM keranjang 
                            WHERE id_barang='$id_barang' AND id_customer='$id_customer'");
$data = mysqli_fetch_array($cek);

if($data){
    // Cek jika ditambah 1, apakah melebihi stok?
    if($data['qty'] + 1 > $barang['stok']){
        echo "<script>alert('Stok {$barang['nama_barang']} hanya tersedia {$barang['stok']} item');window.location='index.php';</script>";
        exit;
    }
    mysqli_query($conn, "UPDATE keranjang SET qty=qty+1 WHERE id='".$data['id']."'");
} else {
    // Cek stok minimal 1
    if($barang['stok'] < 1){
        echo "<script>alert('Stok {$barang['nama_barang']} habis');window.location='index.php';</script>";
        exit;
    }
    mysqli_query($conn, "INSERT INTO keranjang (id_customer,id_barang,qty) 
                         VALUES ('$id_customer','$id_barang',1)");
}

header("location:index.php");
?>