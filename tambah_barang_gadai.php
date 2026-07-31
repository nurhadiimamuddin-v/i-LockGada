<?php
session_start();
include "config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $rahin_id = $_POST['rahin_id'];
    $nama_nasabah = $_POST['nama'];
    $jenis_id = $_POST['jenis_id'];
    $deskripsi_barang = $_POST['deskripsi_barang'];
    $tanggal_digadai = date('Y-m-d');
    $status_barang = $_POST['status_barang'];
    $id_locker = $_POST['id_locker'];

    $pegadaian_id = isset($_SESSION['pegadaian_id']) ? intval($_SESSION['pegadaian_id']) : 0;
    $manager_id = isset($_SESSION['id_manager']) ? intval($_SESSION['id_manager']) : 0;

    // Proses gambar dari kamera (base64)
    $foto_nasabah_data = $_POST['foto_nasabah'];
    $foto_nasabah_filename = '';

    if (!empty($foto_nasabah_data)) {
        $foto_nasabah_data = str_replace('data:image/jpeg;base64,', '', $foto_nasabah_data);
        $foto_nasabah_data = base64_decode($foto_nasabah_data);
        $foto_nasabah_filename = 'uploads/nasabah/foto_nasabah_' . time() . '.jpg';

        // Simpan file ke server
        file_put_contents($foto_nasabah_filename, $foto_nasabah_data);
    }

    // Proses upload file gambar barang
    $foto_barang_filename = '';
    if (isset($_FILES['foto_barang']) && $_FILES['foto_barang']['error'] === UPLOAD_ERR_OK) {
        $foto_barang_tmp = $_FILES['foto_barang']['tmp_name'];
        $foto_barang_name = 'uploads/barang/foto_barang_' . time() . '_' . basename($_FILES['foto_barang']['name']);
        move_uploaded_file($foto_barang_tmp, $foto_barang_name);
        $foto_barang_filename = $foto_barang_name;
    }

    // Cek apakah nasabah sudah ada (berdasarkan nama saja untuk contoh)
    $checkNasabah = mysqli_query($conn, "SELECT id_nasabah FROM nasabah WHERE nama = '" . mysqli_real_escape_string($conn, $nama_nasabah) . "'");
    if (mysqli_num_rows($checkNasabah) > 0) {
        $nasabah = mysqli_fetch_assoc($checkNasabah);
        $nasabah_id = $nasabah['id_nasabah'];
    } else {
        // Tambahkan nasabah baru (tanpa foto)
        $insertNasabah = mysqli_query($conn, "INSERT INTO nasabah (nama) VALUES ('" . mysqli_real_escape_string($conn, $nama_nasabah) . "')");
        $nasabah_id = mysqli_insert_id($conn);
    }

    // Simpan data barang gadai (termasuk path foto_nasabah dan foto_barang)
    $query = "INSERT INTO barang_gadai (
        rahin_id, nasabah_id, jenis_id, deskripsi_barang, tgl_digadai,
        foto_nasabah, foto_barang, status_barang, locker_id, pegadaian_id, manager_id
    ) VALUES (
        '$rahin_id', '$nasabah_id', '$jenis_id', '" . mysqli_real_escape_string($conn, $deskripsi_barang) . "',
        '$tanggal_digadai', '$foto_nasabah_filename', '$foto_barang_filename',
        '$status_barang', '$id_locker', '$pegadaian_id', '$manager_id'
    )";

    if (mysqli_query($conn, $query)) {
        // Update status locker
        mysqli_query($conn, "UPDATE locker SET status = 'terisi' WHERE id_locker = '$id_locker'");

        echo "<script>alert('Data barang berhasil ditambahkan.'); window.location.href='barang_yg_digadaikan.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data.'); window.history.back();</script>";
    }

} else {
    echo "<script>alert('Akses tidak valid.'); window.location.href='barang_yg_digadaikan.php';</script>";
}
?>
