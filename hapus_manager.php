<?php
include 'config.php';

if (isset($_GET['id_manager'])) {
    $id_manager = $_GET['id_manager'];

    // Hapus data berdasarkan id_manager
    $query = "DELETE FROM manager WHERE id_manager = '$id_manager'";
    if (mysqli_query($conn, $query)) {
        // Reset auto-increment agar ID_manager tetap berurutan
        mysqli_query($conn, "SET @num := 0");
        mysqli_query($conn, "UPDATE manager SET id_manager = @num := (@num+1)");
        mysqli_query($conn, "ALTER TABLE manager AUTO_INCREMENT = 1");
    }
}

header("Location: manager.php");
exit;
