<?php
session_start();
include 'config.php'; // Pastikan file koneksi sudah benar

// Pastikan user sudah login
if (!isset($_SESSION['pegadaian_id'])) {
    header("Location: rahin.php?status=error&message=Anda+harus+login+terlebih+dahulu");
    exit;
}

$pegadaian_id = $_SESSION['pegadaian_id'];

// Cek apakah form telah dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $id_rahin = $_POST['id_rahin'];
    $nama_rahin = $_POST['nama_rahin'];
    $nik_rahin = $_POST['nik_rahin'];
    $no_whatsapp = $_POST['no_whatsapp'];
    $email = $_POST['email'] . '@gmail.com'; // Tambahkan domain email

    // Validasi sederhana (opsional)
    if (empty($id_rahin) || empty($nama_rahin) || empty($nik_rahin) || empty($no_whatsapp) || empty($email)) {
        echo "Semua kolom harus diisi!";
        exit;
    }

    // Query update data rahin yang hanya milik pegadaian login
    $query = "UPDATE rahin SET 
                nama_rahin = ?, 
                nik_rahin = ?, 
                no_whatsapp = ?, 
                email = ?
              WHERE id_rahin = ? AND pegadaian_id = ?";

    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die("Query error: " . $conn->error);
    }

    $stmt->bind_param("ssssii", $nama_rahin, $nik_rahin, $no_whatsapp, $email, $id_rahin, $pegadaian_id);

    // Eksekusi dan cek hasilnya
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            header("Location: rahin.php?status=success&message=Data+berhasil+diupdate");
        } else {
            header("Location: rahin.php?status=warning&message=Tidak+ada+perubahan+atau+data+tidak+ditemukan");
        }
        exit;
    } else {
        echo "Gagal mengupdate data: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Akses tidak valid.";
}
