<?php
include 'config.php';

if (isset($_GET['id_jenis_barang'])) {
    $id_jenis_barang = $_GET['id_jenis_barang'];

    // Hapus data berdasarkan id_jenis_barang
    $query = "DELETE FROM jenis_barang WHERE id_jenis_barang = '$id_jenis_barang'";
    if (mysqli_query($conn, $query)) {
        // Reset auto-increment agar ID_jenis_barang tetap berurutan
        mysqli_query($conn, "SET @num := 0");
        mysqli_query($conn, "UPDATE jenis_barang SET id_jenis_barang = @num := (@num+1)");
        mysqli_query($conn, "ALTER TABLE jenis_barang AUTO_INCREMENT = 1");
    }
}

header("Location: jenis_barang.php");
exit;
