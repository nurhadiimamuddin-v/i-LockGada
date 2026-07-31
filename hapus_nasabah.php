<?php
include 'config.php';

if (isset($_GET['id_nasabah'])) {
    $id_nasabah = $_GET['id_nasabah'];

    // Hapus data berdasarkan id_nasabah
    $query = "DELETE FROM nasabah WHERE id_nasabah = '$id_nasabah'";
    if (mysqli_query($conn, $query)) {
        // Reset auto-increment agar ID_nasabah tetap berurutan
        mysqli_query($conn, "SET @num := 0");
        mysqli_query($conn, "UPDATE nasabah SET id_nasabah = @num := (@num+1)");
        mysqli_query($conn, "ALTER TABLE nasabah AUTO_INCREMENT = 1");
    }
}

header("Location: nasabah.php");
exit;
