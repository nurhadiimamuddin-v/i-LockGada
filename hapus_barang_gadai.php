<?php
include "config.php";

// Pastikan koneksi ke database sudah dilakukan sebelumny
// Cek apakah parameter id_barang ada di URL
if (isset($_GET['id_barang'])) {
    $id_barang = intval($_GET['id_barang']);

    // Ambil id_locker dari barang_gadai sebelum dihapus
    $queryGetLocker = "SELECT locker_id FROM barang_gadai WHERE id_barang = $id_barang";
    $resultGetLocker = mysqli_query($conn, $queryGetLocker);

    if (mysqli_num_rows($resultGetLocker) > 0) {
        $row = mysqli_fetch_assoc($resultGetLocker);
        $locker_id = $row['locker_id'];

        // Hapus data barang_gadai
        $queryDelete = "DELETE FROM barang_gadai WHERE id_barang = $id_barang";
        $resultDelete = mysqli_query($conn, $queryDelete);

        if ($resultDelete) {
            // Update status locker menjadi 'belum_terisi'
            $queryUpdateLocker = "UPDATE locker SET status = 'belum_terisi' WHERE id_locker = $locker_id";
            mysqli_query($conn, $queryUpdateLocker);

            // Setel ulang ID agar urut kembali (gunakan dengan hati-hati)
mysqli_query($conn, "SET @num := 0");
mysqli_query($conn, "UPDATE barang_gadai SET id_barang = @num := @num + 1");
mysqli_query($conn, "ALTER TABLE barang_gadai AUTO_INCREMENT = 1");


            // Redirect ke halaman utama dengan pesan sukses
            header("Location: barang_yg_digadaikan.php?message=deleted");
            exit();
        } else {
            echo "Gagal menghapus data barang.";
        }
    } else {
        echo "Data barang tidak ditemukan.";
    }
} else {
    echo "ID barang tidak diberikan.";
}
?>
