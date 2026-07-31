<template>
  <div class="container-scroller">
    <!-- Navbar -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="navbar-brand-wrapper d-flex justify-content-center">
        <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">  
          <a class="navbar-brand brand-logo" href="/"><img src="/images/44.png" alt="logo" style="width: 140px; height: auto;"/></a>
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
                <span class="input-group-text" id="search"><i class="mdi mdi-magnify"></i></span>
              </div>
              <input type="text" class="form-control" placeholder="Search now" aria-label="search" aria-describedby="search">
            </div>
          </li>
        </ul>
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
              <img src="/images/o.png" alt="profile"/>
              <span class="nav-profile-name">Pegadaian Indonesia</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item" @click.prevent="handleLogout" href="#">
                <i class="mdi mdi-logout text-primary"></i> Logout
              </a>
            </div>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="mdi mdi-menu"></span>
        </button>
      </div>
    </nav>
    
    <div class="container-fluid page-body-wrapper">
      <!-- Sidebar -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="/direktur">
              <i class="mdi mdi-home menu-icon"></i>
              <span class="menu-title">Home</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/direktur/pegadaian">
              <i class="mdi mdi-bank menu-icon"></i>
              <span class="menu-title">Pegadaian</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/direktur/jenis-barang">
              <i class="mdi mdi-chart-bar menu-icon"></i>
              <span class="menu-title">Jenis Barang</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/direktur/manager">
              <i class="mdi mdi-account menu-icon"></i>
              <span class="menu-title">Manager</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" @click.prevent="handleLogout" href="#">
              <i class="mdi mdi-logout menu-icon"></i>
              <span class="menu-title">Logout</span>
            </a>
          </li>
        </ul>
      </nav>
      
      <!-- Main Content -->
      <slot />

    </div>
  </div>
</template>

<script setup>
const { logout } = useAuth()

const handleLogout = () => {
  if (confirm('Apakah Anda yakin ingin keluar?')) {
    logout()
  }
}
</script>
