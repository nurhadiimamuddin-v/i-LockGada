<?php
session_start();
include "config.php";

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $id_nasabah = $_POST['id_nasabah'];
    $nama = trim($_POST['nama']);
    $nik = trim($_POST['nik']);
    $tempat_lahir = trim($_POST['tempat_lahir']);
    $tanggal_lahir = trim($_POST['tanggal_lahir']);
    $password = $_POST['password'];

    // Validasi input wajib
    if (empty($id_nasabah) || empty($nama) || empty($nik) || empty($tempat_lahir) || empty($tanggal_lahir) || empty($password)) {
        $_SESSION['message'] = "Semua field harus diisi.";
        $_SESSION['message_type'] = "danger";
        header("Location: nasabah.php");
        exit();
    }

    // Cek apakah nik sudah ada di nasabah lain
    $check_stmt = $conn->prepare("SELECT id_nasabah FROM nasabah WHERE nik = ? AND id_nasabah != ?");
    $check_stmt->bind_param("si", $nik, $id_nasabah);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $_SESSION['message'] = "NIK sudah digunakan oleh nasabah lain.";
        $_SESSION['message_type'] = "danger";
        header("Location: nasabah.php");
        exit();
    }

    // Update data nasabah
    $stmt = $conn->prepare("UPDATE nasabah SET nama = ?, nik = ?, tempat_lahir = ?, tanggal_lahir = ?, password = ? WHERE id_nasabah = ?");
    $stmt->bind_param("sssssi", $nama, $nik, $tempat_lahir, $tanggal_lahir, $password, $id_nasabah);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Data nasabah berhasil diperbarui.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Gagal memperbarui data nasabah: " . $conn->error;
        $_SESSION['message_type'] = "danger";
    }

    header("Location: nasabah.php");
    exit();
} else {
    header("Location: nasabah.php");
    exit();
}
?>
