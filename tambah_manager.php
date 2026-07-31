<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $nama = $_POST['nama'];
  $username = $_POST['username'];
  $password = $_POST['password']; // Password akan di-hash
  $pegadaian_id = $_POST['pegadaian_id'];

  // Hash password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  $sql = "INSERT INTO Manager (nama, username, password, pegadaian_id) 
      VALUES (?, ?, ?, ?)";
  
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "sssi", $nama, $username, $hashed_password, $pegadaian_id);
  
  if (mysqli_stmt_execute($stmt)) {
    // Redirect langsung tanpa alert
    header("Location: manager.php");
    exit();
  } else {
    // Jika error, bisa redirect dengan parameter error (opsional)
    header("Location: manager.php?error=1");
    exit();
  }
} else {
  // Jika akses langsung tanpa POST, redirect
  header("Location: manager.php");
  exit();
}
?>
