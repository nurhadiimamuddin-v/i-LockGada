<template>
  <div class="container-scroller">
    <!-- Navbar -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="navbar-brand-wrapper d-flex justify-content-center">
        <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">  
          <NuxtLink class="navbar-brand brand-logo" to="/"><img src="/images/44.png" alt="logo" style="width: 140px; height: auto;"/></NuxtLink>
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
            <NuxtLink class="nav-link" to="/direktur">
              <i class="mdi mdi-home menu-icon"></i>
              <span class="menu-title">Home</span>
            </NuxtLink>
          </li>
          <li class="nav-item">
            <NuxtLink class="nav-link" to="/direktur/pegadaian">
              <i class="mdi mdi-bank menu-icon"></i>
              <span class="menu-title">Pegadaian</span>
            </NuxtLink>
          </li>
          <li class="nav-item">
            <NuxtLink class="nav-link" to="/direktur/jenis-barang">
              <i class="mdi mdi-chart-bar menu-icon"></i>
              <span class="menu-title">Jenis Barang</span>
            </NuxtLink>
          </li>
          <li class="nav-item">
            <NuxtLink class="nav-link" to="/direktur/manager">
              <i class="mdi mdi-account menu-icon"></i>
              <span class="menu-title">Manager</span>
            </NuxtLink>
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

useHead({
  link: [
    { rel: 'stylesheet', href: '/vendors/mdi/css/materialdesignicons.min.css' },
    { rel: 'stylesheet', href: '/vendors/base/vendor.bundle.base.css' },
    { rel: 'stylesheet', href: 'https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css' },
    { rel: 'stylesheet', href: 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' },
    { rel: 'stylesheet', href: '/css/style.css' }
  ],
  script: [
    { src: '/vendors/base/vendor.bundle.base.js', body: true },
    { src: 'https://code.jquery.com/jquery-3.7.1.min.js', body: true },
    { src: 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', body: true },
    { src: 'https://cdn.datatables.net/2.2.2/js/dataTables.js', body: true },
    { src: '/js/off-canvas.js', body: true },
    { src: '/js/hoverable-collapse.js', body: true },
    { src: '/js/template.js', body: true }
  ]
})

const handleLogout = () => {
  if (confirm('Apakah Anda yakin ingin keluar?')) {
    logout()
  }
}
</script>
