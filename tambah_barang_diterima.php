<?php
include "config.php";
session_start();

// Validasi data input
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nik_rahin = $_POST['nik_rahin'] ?? '';
    $tgl_diterima = $_POST['tgl_diterima'] ?? '';
    $finishing_foto_nasabah_data = $_POST['finishing_foto_nasabah'];
    $finishing_foto_nasabah_filename = '';

    if (!empty($finishing_foto_nasabah_data)) {
        $finishing_foto_nasabah_data = str_replace('data:image/jpeg;base64,', '', $finishing_foto_nasabah_data);
        $finishing_foto_nasabah_data = base64_decode($finishing_foto_nasabah_data);
        $finishing_foto_nasabah_filename = 'uploads/verifikasi_nasabah/finishing_foto_nasabah_' . time() . '.jpg';

        // Simpan file ke server
        file_put_contents($finishing_foto_nasabah_filename, $finishing_foto_nasabah_data);

        // Set filename for database insert
        $finishing_foto_nasabah = $finishing_foto_nasabah_filename;
    } else {
        // Jika tidak ada foto, set string kosong atau handle sesuai kebutuhan
        $finishing_foto_nasabah = '';
    }
    
    // Upload foto barang
    if (isset($_FILES['finishing_foto_barang']) && $_FILES['finishing_foto_barang']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/verifikasi_barang/finishing_foto_barang_';
        $fotoBarangName = time() . '_' . basename($_FILES['finishing_foto_barang']['name']);
        $uploadPath = $uploadDir . $fotoBarangName;

        if (!move_uploaded_file($_FILES['finishing_foto_barang']['tmp_name'], $uploadPath)) {
            die("Gagal mengunggah foto barang.");
        }
    } else {
        die("Foto barang tidak ditemukan atau gagal diunggah.");
    }

    // Cari data barang_gadai yang masih digadai berdasarkan nik_rahin
    $query = "
        SELECT * FROM barang_gadai bg
        JOIN rahin r ON bg.rahin_id = r.id_rahin
        WHERE r.nik_rahin = '$nik_rahin' AND bg.status_barang = 'digadai'
        LIMIT 1
    ";
    $result = mysqli_query($conn, $query);
    if (!$result || mysqli_num_rows($result) == 0) {
        die("Data barang tidak ditemukan atau sudah diambil.");
    }

    $barang = mysqli_fetch_assoc($result);
    $barang_id = $barang['id_barang'];

    // Siapkan data untuk insert ke barang_diambil
    $nasabah_id   = $barang['nasabah_id'];
    $locker_id    = $barang['locker_id'];
    $jenis_id     = $barang['jenis_id'];
    $rahin_id     = $barang['rahin_id'];
    $manager_id   = $barang['manager_id'];
    $pegadaian_id = $barang['pegadaian_id'];

    // Simpan ke tabel barang_diambil
    $queryInsert = "
        INSERT INTO barang_diambil (
            tgl_diterima, finishing_foto_nasabah, finishing_foto_barang, 
            nasabah_id, locker_id, jenis_id, rahin_id, barang_id, manager_id, pegadaian_id
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
        )
    ";
    $stmt = mysqli_prepare($conn, $queryInsert);
    mysqli_stmt_bind_param($stmt, "ssssssssss", 
        $tgl_diterima, $finishing_foto_nasabah_filename, $uploadPath, 
        $nasabah_id, $locker_id, $jenis_id, $rahin_id, $barang_id, $manager_id, $pegadaian_id
    );

    if (mysqli_stmt_execute($stmt)) {
        // Update status_barang di barang_gadai
        mysqli_query($conn, "UPDATE barang_gadai SET status_barang = 'diambil' WHERE id_barang = '$barang_id'");

        // Update status_loker di locker
        mysqli_query($conn, "UPDATE locker SET status = 'belum_terisi' WHERE id_locker = '$locker_id'");

        echo "<script>alert('Barang berhasil diterima dan data disimpan.'); window.location.href='barang_yg_diambil.php';</script>";
    } else {
        echo "Gagal menyimpan data barang diambil: " . mysqli_error($conn);
    }
} else {
    echo "Permintaan tidak valid.";
}
?>
