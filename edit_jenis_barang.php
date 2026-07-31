<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $id_jenis_barang = $_POST['id_jenis_barang'];
  $kode_barang = 'KDBRG' . $_POST['kode_barang'];
  $jenis_barang = $_POST['jenis_barang'];

  if (empty($id_jenis_barang) || empty($kode_barang) || empty($jenis_barang)) {
    echo json_encode([
      'status' => 'error',
      'message' => 'Semua field harus diisi!'
    ]);
    exit;
  }

  try {
    // Cek apakah kode_barang sudah digunakan
    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM jenis_barang WHERE kode_barang = ? AND id_jenis_barang != ?");
    $checkStmt->bind_param("si", $kode_barang, $id_jenis_barang);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($count > 0) {
      echo "<script>
      alert('Kode barang telah digunakan, silahkan gunakan kode yang lain!');
      window.location.href = 'jenis_barang.php';
      </script>";
      exit;
    }

    // Update data jika kode_barang belum digunakan
    $stmt = $conn->prepare("UPDATE jenis_barang SET 
                kode_barang = ?, 
                jenis_barang = ? 
                WHERE id_jenis_barang = ?");
    $stmt->bind_param("ssi", $kode_barang, $jenis_barang, $id_jenis_barang);
    $result = $stmt->execute();

    if ($result) {
      header("Location: jenis_barang.php");
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
?>