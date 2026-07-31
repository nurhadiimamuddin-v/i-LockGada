<?php
include "config.php";

// Cek apakah parameter id_barang_diambil ada di URL
if (isset($_GET['id_barang_diambil'])) {
  $id_barang_diambil = intval($_GET['id_barang_diambil']);

  // Hapus data barang_gadai
  $queryDelete = "DELETE FROM barang_diambil WHERE id_barang_diambil = $id_barang_diambil";
  $resultDelete = mysqli_query($conn, $queryDelete);

  if ($resultDelete) {
    // Setel ulang ID agar urut kembali (gunakan dengan hati-hati)
    mysqli_query($conn, "SET @num := 0");
    mysqli_query($conn, "UPDATE barang_diambil SET id_barang_diambil = @num := @num + 1");
    mysqli_query($conn, "ALTER TABLE barang_diambil AUTO_INCREMENT = 1");

    // Redirect ke halaman utama dengan pesan sukses
    header("Location: barang_yg_diambil.php?message=deleted");
    exit();
  } else {
    echo "Gagal menghapus data barang.";
  }
} else {
  echo "ID barang tidak diberikan.";
}
?>
