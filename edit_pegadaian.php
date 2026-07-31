<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $id = $_POST['id'];
  $kode_pegadaian = 'PGD' . $_POST['kode_pegadaian'];
  $lokasi_pegadaian = $_POST['lokasi_pegadaian'];

  if (empty($id) || empty($kode_pegadaian) || empty($lokasi_pegadaian)) {
    echo json_encode([
      'status' => 'error',
      'message' => 'Semua field harus diisi!'
    ]);
    exit;
  }

  try {
    // Cek apakah kode_pegadaian sudah digunakan oleh ID lain
    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM pegadaian WHERE kode_pegadaian = ? AND id != ?");
    $checkStmt->bind_param("si", $kode_pegadaian, $id);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($count > 0) {
      echo "<script>
        alert('Kode pegadaian telah digunakan, silakan gunakan kode yang lain!');
        window.location.href = 'pegadaian.php';
      </script>";
      exit;
    }

    // Jika kode unik, lakukan update
    $stmt = $conn->prepare("UPDATE pegadaian SET 
                kode_pegadaian = ?, 
                lokasi_pegadaian = ? 
                WHERE id = ?");
    $stmt->bind_param("ssi", $kode_pegadaian, $lokasi_pegadaian, $id);
    $result = $stmt->execute();

    if ($result) {
      header("Location: pegadaian.php");
      exit;
    } else {
      echo json_encode([
        'status' => 'error',
        'message' => 'Gagal memperbarui data!'
      ]);
    }

    $stmt->close();
  } catch (Exception $e) {
    echo json_encode([
      'status' => 'error',
      'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    ]);
  }
} else {
  echo json_encode([
    'status' => 'error',
    'message' => 'Metode request tidak valid!'
  ]);
}

$conn->close();
