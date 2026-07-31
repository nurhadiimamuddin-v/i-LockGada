<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $kode_pegadaian = mysqli_real_escape_string($conn, $_POST['kode_pegadaian']);
  $lokasi_pegadaian = mysqli_real_escape_string($conn, $_POST['lokasi_pegadaian']);

  // Tambahkan prefix "PGD" jika belum ada
  if (strpos($kode_pegadaian, 'PGD') !== 0) {
    $kode_pegadaian = 'PGD' . $kode_pegadaian;
  }

  // Validasi input
  if (!empty($kode_pegadaian) && !empty($lokasi_pegadaian)) {
    // Cek apakah kode_pegadaian sudah ada di database
    $check_query = "SELECT * FROM pegadaian WHERE kode_pegadaian = '$kode_pegadaian'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
      // Jika kode sudah ada, tampilkan alert
      echo "<script>
              alert('Kode pegadaian telah tersedia !!! Silahkan gunakan kode lain.');
              window.location.href = 'pegadaian.php';
            </script>";
      exit();
    } else {
      // Jika kode belum ada, lakukan insert
      $query = "INSERT INTO pegadaian (id, kode_pegadaian, lokasi_pegadaian) VALUES (NULL, '$kode_pegadaian', '$lokasi_pegadaian')";
      if (mysqli_query($conn, $query)) {
        header('Location: pegadaian.php?status=success');
        exit();
      } else {
        header('Location: pegadaian.php?status=error');
        exit();
      }
    }
  } else {
    header('Location: pegadaian.php?status=empty');
    exit();
  }
} else {
  header('Location: pegadaian.php');
  exit();
}
?>
