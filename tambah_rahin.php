<?php
session_start();
include 'config.php';

// Cek apakah form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pastikan pegadaian sudah login
    if (!isset($_SESSION['pegadaian_id'])) {
        header("Location: rahin.php?status=error&message=Anda+harus+login+terlebih+dahulu");
        exit();
    }

    $nama_rahin = $_POST['nama_rahin'];
    $nik_rahin = $_POST['nik_rahin'];
    $no_whatsapp = $_POST['no_whatsapp'];
    $kode_locker = $_POST['kode_locker'];
    $email = $kode_locker . "@gmail.com"; // Email dibuat dari input dan ditambahkan domain
    $pegadaian_id = $_SESSION['pegadaian_id']; // Ambil dari session

    // Simpan data ke database dengan pegadaian_id
    $query = "INSERT INTO rahin (nama_rahin, nik_rahin, no_whatsapp, email, pegadaian_id) 
              VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $nama_rahin, $nik_rahin, $no_whatsapp, $email, $pegadaian_id);

    if ($stmt->execute()) {
        // Jika berhasil, redirect ke halaman rahin.php
        header("Location: rahin.php?status=success&message=Rahin+berhasil+ditambahkan");
        exit();
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Error: " . $stmt->error;
    }
} else {
    // Jika akses langsung ke file ini tanpa submit form
    header("Location: rahin.php");
    exit();
}
?>
