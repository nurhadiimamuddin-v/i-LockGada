<?php
include 'config.php';

if (isset($_GET['id_locker'])) {
    $id_locker = $_GET['id_locker'];

    // Hapus data berdasarkan id_locker
    $query = "DELETE FROM locker WHERE id_locker = '$id_locker'";
    if (mysqli_query($conn, $query)) {
        // Reset auto-increment agar ID_locker tetap berurutan
        mysqli_query($conn, "SET @num := 0");
        mysqli_query($conn, "UPDATE locker SET id_locker = @num := (@num+1)");
        mysqli_query($conn, "ALTER TABLE locker AUTO_INCREMENT = 1");
    }
}

header("Location: locker.php");
exit;
