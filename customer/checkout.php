<?php
session_start();
include '../config/koneksi.php';

// Cek login
if (!isset($_SESSION['customer'])) {
    header("Location: login.php");
    exit;
}

$id_customer = $_SESSION['customer'];

// Ambil data keranjang
$data = mysqli_query($conn, "
    SELECT keranjang.*, barang.harga, barang.stok as stok_tersedia 
    FROM keranjang 
    JOIN barang ON keranjang.id_barang = barang.id
    WHERE id_customer='$id_customer'
");

$total = 0;
$items = [];
$error = false;

// Cek stok dan hitung total
while($d = mysqli_fetch_array($data)){
    $subtotal = $d['harga'] * $d['qty'];
    $total += $subtotal;
    
    // Cek apakah stok mencukupi
    if($d['qty'] > $d['stok_tersedia']){
        $error = true;
        echo "<script>alert('Stok {$d['nama_barang']} tidak mencukupi! Stok tersedia: {$d['stok_tersedia']}');window.location='keranjang.php';</script>";
        exit;
    }
    
    $items[] = [
        'id_barang' => $d['id_barang'],
        'qty' => $d['qty'],
        'subtotal' => $subtotal,
        'nama_barang' => $d['nama_barang']
    ];
}

// Jika ada error stok, hentikan proses
if($error || count($items) == 0){
    header("Location: keranjang.php");
    exit;
}

// Mulai transaction (agar semua query berhasil atau gagal semua)
mysqli_begin_transaction($conn);

try {
    // 1. Insert ke transaksi
    $query_transaksi = mysqli_query($conn, "INSERT INTO transaksi (id_customer, total, status, tanggal) 
                                            VALUES ('$id_customer', '$total', 'Pending', NOW())");
    
    if(!$query_transaksi){
        throw new Exception("Gagal menyimpan transaksi");
    }
    
    $id_transaksi = mysqli_insert_id($conn);
    
    // 2. Insert detail transaksi dan kurangi stok
    foreach($items as $item){
        // Insert detail transaksi
        $query_detail = mysqli_query($conn, "INSERT INTO detail_transaksi (id_transaksi, id_barang, qty, subtotal)
                                            VALUES ('$id_transaksi', '".$item['id_barang']."', '".$item['qty']."', '".$item['subtotal']."')");
        
        if(!$query_detail){
            throw new Exception("Gagal menyimpan detail transaksi");
        }
        
        // Kurangi stok barang
        $query_stok = mysqli_query($conn, "UPDATE barang 
                                          SET stok = stok - ".$item['qty']." 
                                          WHERE id = '".$item['id_barang']."' AND stok >= ".$item['qty']);
        
        if(mysqli_affected_rows($conn) == 0){
            throw new Exception("Stok {$item['nama_barang']} tidak mencukupi");
        }
    }
    
    // 3. Kosongkan keranjang
    $query_hapus = mysqli_query($conn, "DELETE FROM keranjang WHERE id_customer='$id_customer'");
    
    if(!$query_hapus){
        throw new Exception("Gagal mengosongkan keranjang");
    }
    
    // Commit semua perubahan
    mysqli_commit($conn);
    
    echo "<script>alert('Checkout berhasil!');window.location='riwayat.php';</script>";
    
} catch (Exception $e) {
    // Rollback jika ada error
    mysqli_rollback($conn);
    echo "<script>alert('Checkout gagal: " . $e->getMessage() . "');window.location='keranjang.php';</script>";
}
?>