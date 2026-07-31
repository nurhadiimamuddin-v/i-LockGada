<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['pegadaian_id'])) {
        $_SESSION['message'] = "Anda harus login terlebih dahulu.";
        $_SESSION['message_type'] = "danger";
        header("Location: nasabah.php");
        exit();
    }

    $pegadaian_id = $_SESSION['pegadaian_id'];

    $nama = trim($_POST['nama']);
    $nik = trim($_POST['nik']);
    $tempat_lahir = trim($_POST['tempat_lahir']);
    $tanggal_lahir = trim($_POST['tanggal_lahir']);
    $password = $_POST['password'];

    // Validasi input wajib
    if (empty($nama) || empty($nik) || empty($tempat_lahir) || empty($tanggal_lahir) || empty($password)) {
        $_SESSION['message'] = "Semua field harus diisi.";
        $_SESSION['message_type'] = "danger";
        header("Location: nasabah.php");
        exit();
    }

    // Cek NIK unik
    $check_stmt = $conn->prepare("SELECT id_nasabah FROM nasabah WHERE nik = ?");
    $check_stmt->bind_param("s", $nik);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $_SESSION['message'] = "NIK sudah terdaftar.";
        $_SESSION['message_type'] = "danger";
        header("Location: nasabah.php");
        exit();
    }

    // Insert data tanpa hash password
    $stmt = $conn->prepare("INSERT INTO nasabah (nama, nik, tempat_lahir, tanggal_lahir, password, pegadaian_id) 
                            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $nama, $nik, $tempat_lahir, $tanggal_lahir, $password, $pegadaian_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Data nasabah berhasil ditambahkan.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Gagal menambahkan data nasabah: " . $conn->error;
        $_SESSION['message_type'] = "danger";
    }

    header("Location: nasabah.php");
    exit();
} else {
    header("Location: nasabah.php");
    exit();
}
?>
