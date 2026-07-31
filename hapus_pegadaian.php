<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Hapus data berdasarkan id
    $query = "DELETE FROM pegadaian WHERE id = '$id'";
    if (mysqli_query($conn, $query)) {
        // Reset auto-increment agar ID tetap berurutan
        mysqli_query($conn, "SET @num := 0");
        mysqli_query($conn, "UPDATE pegadaian SET id = @num := (@num+1)");
        mysqli_query($conn, "ALTER TABLE pegadaian AUTO_INCREMENT = 1");
    }
}

header("Location: pegadaian.php");
exit;
