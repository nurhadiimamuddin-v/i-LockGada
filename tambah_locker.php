<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pastikan manager sudah login
    if (!isset($_SESSION['pegadaian_id'])) {
        header("Location: locker.php?status=error&message=Anda+harus+login+terlebih+dahulu");
        exit();
    }

    $kode_input = $_POST['kode_locker'];
    $kode_locker = 'LCKR' . $kode_input; // Gabungkan prefix
    $pegadaian_id = $_SESSION['pegadaian_id'];

    // Validasi input kosong
    if (empty($kode_input)) {
        header("Location: locker.php?status=error&message=Kode+Locker+tidak+boleh+kosong");
        exit();
    }

    // Cek apakah kode locker sudah ada
    $check = $conn->prepare("SELECT id_locker FROM locker WHERE kode_locker = ?");
    $check->bind_param("s", $kode_locker);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        header("Location: locker.php?status=error&message=Kode+Locker+sudah+ada");
        exit();
    }

    // Insert locker baru terkait dengan pegadaian manager
    $stmt = $conn->prepare("INSERT INTO locker (kode_locker, status, pegadaian_id) VALUES (?, 'belum_terisi', ?)");
    $stmt->bind_param("si", $kode_locker, $pegadaian_id);

    if ($stmt->execute()) {
        header("Location: locker.php?status=success&message=Locker+berhasil+ditambahkan");
    } else {
        header("Location: locker.php?status=error&message=" . urlencode($conn->error));
    }

    exit();
} else {
    header("Location: locker.php");
    exit();
}
?>
