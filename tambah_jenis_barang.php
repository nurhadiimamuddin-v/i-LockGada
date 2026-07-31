<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $kode_barang = mysqli_real_escape_string($conn, $_POST['kode_barang']);
  $jenis_barang = mysqli_real_escape_string($conn, $_POST['jenis_barang']);

  // Tambahkan prefix "KDBRG" jika belum ada
  if (strpos($kode_barang, 'KDBRG') !== 0) {
    $kode_barang = 'KDBRG' . $kode_barang;
  }

  // Valid_jenis_barangasi input
  if (!empty($kode_barang) && !empty($jenis_barang)) {
    // Cek apakah kode_barang sudah ada di database
    $check_query = "SELECT * FROM jenis_barang WHERE kode_barang = '$kode_barang'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
      // Jika kode sudah ada, tampilkan alert
      echo "<script>
              alert('Kode barang telah tersedia !!! Silahkan gunakan kode lain.');
              window.location.href = 'jenis_barang.php';
            </script>";
      exit();
    } else {
      // Jika kode belum ada, lakukan insert
      $query = "INSERT INTO jenis_barang (id_jenis_barang, kode_barang, jenis_barang) VALUES (NULL, '$kode_barang', '$jenis_barang')";
      if (mysqli_query($conn, $query)) {
        header('Location: jenis_barang.php?status=success');
        exit();
      } else {
        header('Location: jenis_barang.php?status=error');
        exit();
      }
    }
  } else {
    header('Location: jenis_barang.php?status=empty');
    exit();
  }
} else {
  header('Location: jenis_barang.php');
  exit();
}
?>
