<?php
session_start();
include 'config.php'; // koneksi ke database

$username = $_POST['username'];
$password = $_POST['password'];

// Cek direktur
$query = mysqli_query($conn, "SELECT * FROM direktur WHERE username='$username' AND password='$password'");
if(mysqli_num_rows($query) > 0){
    $data = mysqli_fetch_assoc($query);
    $_SESSION['id_direktur'] = $data['id_direktur'];
    $_SESSION['nama'] = $data['nama'];
    $_SESSION['username'] = $data['username'];
    $_SESSION['role'] = 'direktur';
    header("Location: direktur_dashboard.php");
    exit;
}

// Cek manager
$query = mysqli_query($conn, "SELECT manager.*, pegadaian.kode_pegadaian, pegadaian.lokasi_pegadaian FROM manager 
                              JOIN pegadaian ON manager.pegadaian_id = pegadaian.id 
                              WHERE manager.username='$username'");
if(mysqli_num_rows($query) > 0){
    $data = mysqli_fetch_assoc($query);
    if(password_verify($password, $data['password'])){
        $_SESSION['id_manager'] = $data['id_manager'];
        $_SESSION['kode_pegadaian'] = $data['kode_pegadaian'];
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['pegadaian_id'] = $data['pegadaian_id'];
        $_SESSION['lokasi_pegadaian'] = $data['lokasi_pegadaian'];
        $_SESSION['role'] = 'manager';
        header("Location: manager_dashboard.php");
        exit;
    }
}
$query = mysqli_query($conn, "SELECT nasabah.*, manager.id_manager, manager.pegadaian_id, pegadaian.kode_pegadaian 
    FROM nasabah 
    LEFT JOIN manager ON nasabah.pegadaian_id = manager.pegadaian_id 
    LEFT JOIN pegadaian ON manager.pegadaian_id = pegadaian.id 
    WHERE nasabah.nik='$username' AND nasabah.password='$password'");

if (mysqli_num_rows($query) > 0) {
    $data = mysqli_fetch_assoc($query);
    $_SESSION['id_nasabah'] = $data['id_nasabah'];
    $_SESSION['nama'] = $data['nama'];
    $_SESSION['nik'] = $data['nik'];
    $_SESSION['tempat_lahir'] = $data['tempat_lahir'];
    $_SESSION['tanggal_lahir'] = $data['tanggal_lahir'];
    $_SESSION['kode_pegadaian'] = $data['kode_pegadaian'];
    $_SESSION['pegadaian_id'] = $data['pegadaian_id'];
    $_SESSION['id_manager'] = $data['id_manager'];
    $_SESSION['role'] = 'nasabah';
    header("Location: nasabah_dashboard.php");
    exit;
}

// Jika tidak cocok dengan semuanya
echo "<script>alert('Login gagal! Data tidak ditemukan'); window.location='eror.php';</script>";
?>
