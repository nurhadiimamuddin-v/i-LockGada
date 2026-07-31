<?php
include 'config.php';

if (isset($_GET['id_rahin'])) {
    $id_rahin = $_GET['id_rahin'];

    // Hapus data berdasarkan id_rahin
    $query = "DELETE FROM rahin WHERE id_rahin = '$id_rahin'";
    if (mysqli_query($conn, $query)) {
        // Reset auto-increment agar ID_rahin tetap berurutan
        mysqli_query($conn, "SET @num := 0");
        mysqli_query($conn, "UPDATE rahin SET id_rahin = @num := (@num+1)");
        mysqli_query($conn, "ALTER TABLE rahin AUTO_INCREMENT = 1");
    }
}

header("Location: rahin.php");
exit;
