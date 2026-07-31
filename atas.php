<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'manager') {
  header("Location: login.php");
  exit();
}

// Ambil pegadaian_id dari session, jika ada

// Pastikan koneksi database sudah dibuat sebelumnya, misal $conn
// Query untuk ambil data nasabah yang terdaftar di pegadaian ini
?>

   
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Smart Locker Pegadaian</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="vendors/base/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <link rel="stylesheet" href="vendors/datatables.net-bs4/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>


  <link rel="stylesheet" href="/DataTables/datatables.css" />

  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/9.png" />
</head>
<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="navbar-brand-wrapper d-flex justify-content-center">
        <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">  
          <a class="navbar-brand brand-logo" href="index.html"><img src="images/44.png" alt="logo" style="width: 140px; height: auto;"/></a>
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-sort-variant"></span>
          </button>
        </div>  
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <ul class="navbar-nav mr-lg-4 w-100">
          <li class="nav-item nav-search d-none d-lg-block w-100">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text" id="search">
                  <i class="mdi mdi-magnify"></i>
                </span>
              </div>
              <input type="text" class="form-control" placeholder="Search now" aria-label="search" aria-describedby="search">
            </div>
          </li>
        </ul>
        <ul class="navbar-nav navbar-nav-right">
        
         
         <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
              <img src="images/o.png" alt="profile"/>
              <span class="nav-profile-name">Pegadaian Indonesia</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
               <a class="dropdown-item" onclick="return confirm('Apakah Anda yakin ingin keluar?')" href="logout.php">
                <i class="mdi mdi-logout text-primary"></i>
                Logout
              </a>
            </div>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="mdi mdi-menu"></span>
        </button>
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="manager_dashboard.php">
              <i class="mdi mdi-home menu-icon"></i>
              <span class="menu-title">Home</span>
            </a>
          </li>
          
          <li class="nav-item">
            <a class="nav-link" href="nasabah.php">
              <i class="mdi mdi-account-key menu-icon"></i>
              <span class="menu-title">Pegawai Pegadaian</span>
            </a>
          </li>
          
          <li class="nav-item">
            <a class="nav-link" href="rahin_pegadaian.php">
              <i class="mdi mdi-account-search menu-icon"></i>
              <span class="menu-title">Data Nasabah</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="locker.php">
              <i class="mdi mdi-lock menu-icon"></i>
              <span class="menu-title">Locker</span>
            </a>
          </li>
         
          <li class="nav-item">
            <a class="nav-link" href="riwayat_gadai.php">
              <i class="mdi mdi-file menu-icon"></i>
              <span class="menu-title">Barang yang digadaikan</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="riwayat_ambil.php">
              <i class="mdi mdi-check menu-icon"></i>
              <span class="menu-title">Barang yang telah diambil</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="riwayat.php">
              <i class="mdi mdi-chart-bar menu-icon"></i>
              <span class="menu-title">Riwayat Pegadaian</span>
            </a>
          </li>
        
          
         
          <li class="nav-item">
            <a class="nav-link" onclick="return confirm('Apakah Anda yakin ingin keluar?')" href="logout.php">
              <i class="mdi mdi-logout menu-icon"></i>
              <span class="menu-title">Logout</span>
            </a>
          </li>
         
        </ul>
      </nav>