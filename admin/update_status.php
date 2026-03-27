<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';

// Cek login admin
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$status = $_GET['status'];

// Mulai transaction
mysqli_begin_transaction($conn);

try {
    // Ambil status sebelumnya
    $query_cek = mysqli_query($conn, "SELECT status FROM transaksi WHERE id='$id'");
    $data = mysqli_fetch_array($query_cek);
    $status_sebelum = $data['status'];
    
    // Update status transaksi
    $query_update = mysqli_query($conn, "UPDATE transaksi SET status='$status' WHERE id='$id'");
    
    if(!$query_update){
        throw new Exception("Gagal update status");
    }
    
    // Jika transaksi dibatalkan, kembalikan stok
    if($status == 'Batal' && $status_sebelum != 'Batal'){
        // Ambil detail transaksi
        $query_detail = mysqli_query($conn, "SELECT * FROM detail_transaksi WHERE id_transaksi='$id'");
        
        while($detail = mysqli_fetch_array($query_detail)){
            // Kembalikan stok
            $query_restore = mysqli_query($conn, "UPDATE barang 
                                                  SET stok = stok + ".$detail['qty']." 
                                                  WHERE id = '".$detail['id_barang']."'");
            
            if(!$query_restore){
                throw new Exception("Gagal mengembalikan stok");
            }
        }
    }
    
    // Jika transaksi sebelumnya batal, dan sekarang selesai, kurangi stok lagi
    if($status == 'Selesai' && $status_sebelum == 'Batal'){
        $query_detail = mysqli_query($conn, "SELECT * FROM detail_transaksi WHERE id_transaksi='$id'");
        
        while($detail = mysqli_fetch_array($query_detail)){
            $query_reduce = mysqli_query($conn, "UPDATE barang 
                                                 SET stok = stok - ".$detail['qty']." 
                                                 WHERE id = '".$detail['id_barang']."' AND stok >= ".$detail['qty']);
            
            if(mysqli_affected_rows($conn) == 0){
                throw new Exception("Stok tidak mencukupi untuk transaksi ini");
            }
        }
    }
    
    mysqli_commit($conn);
    
} catch (Exception $e) {
    mysqli_rollback($conn);
    echo "<script>alert('Error: " . $e->getMessage() . "');window.location='dashboard.php';</script>";
    exit;
}

header("Location: dashboard.php");
exit;
?>