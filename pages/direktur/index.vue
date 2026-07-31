<template>
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between flex-wrap">
          <div class="d-flex align-items-end flex-wrap">
            <div class="mr-md-3 mr-xl-5">
              <h2>Welcome Back to i-LockGada Pegadaian,</h2>
              <p class="mb-md-0"> Selamat Datang kepada Bapak <mark class="bg-success text-black">Direktur</mark> Pegadaian (BUMN) </p>
              <p class="mb-md-0"> Mengatasi Masalah tanpa Masalah </p>
            </div>
            <div class="d-flex">
              <img src="/images/ty.png" alt="logo" style="height: 83px; margin-left: 250px;" class="d-none d-md-block">
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Profile & Pegadaian Tabs -->
    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body dashboard-tabs p-0">
            <ul class="nav nav-tabs px-4" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="overview-tab" data-bs-toggle="tab" href="#overview" role="tab">Profile Direktur</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="sales-tab" data-bs-toggle="tab" href="#sales" role="tab">Pegadaian</a>
              </li>
            </ul>
            <div class="tab-content py-0 px-0">
              <!-- Profile Tab -->
              <div class="tab-pane fade show active" id="overview" role="tabpanel">
                <div class="d-flex flex-wrap justify-content-xl-between">
                  <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                    <i class="mdi mdi-account mr-3 icon-lg text-success"></i>
                    <div class="d-flex flex-column justify-content-around">
                      <small class="mb-1 text-muted">Nama</small>
                      <h5 class="mr-2 mb-0">{{ userName || '-' }}</h5>
                    </div>
                  </div>
                  <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                    <i class="mdi mdi-clipboard-account mr-3 icon-lg" style="color: #20a1ad;"></i>
                    <div class="d-flex flex-column justify-content-around">
                      <small class="mb-1 text-muted">Jabatan</small>
                      <h5 class="mr-2 mb-0">{{ userRole || '-' }}</h5>
                    </div>
                  </div>
                  <div class="d-flex py-3 border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                    <i class="mdi mdi-key mr-3 icon-lg" style="color: #152453;"></i>
                    <div class="d-flex flex-column justify-content-around">
                      <small class="mb-1 text-muted">Username</small>
                      <h5 class="mr-2 mb-0">{{ userUsername || '-' }}</h5>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Pegadaian Tab -->
              <div class="tab-pane fade" id="sales" role="tabpanel">
                <div class="d-flex flex-wrap justify-content-xl-between">
                  <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                    <i class="mdi mdi-bank mr-3 icon-lg text-success"></i>
                    <div class="d-flex flex-column justify-content-around">
                      <small class="mb-1 text-muted">Jumlah Pegadaian yang sudah terdaftar</small>
                      <h5 class="mr-2 mb-0">{{ totalPegadaian }} Pegadaian</h5>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Data Table -->
    <div class="row">
      <div class="col-md-12 stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <p class="card-title mb-0">Data Pegadaian Di Seluruh Indonesia</p>
            </div>
            <div class="table-responsive">
              <table id="direkturDashboardTable" class="display" style="width:100%">
                <thead>
                  <tr>
                    <th>Kode Pegadaian</th>
                    <th>Lokasi Pegadaian</th>
                    <th>Nama Manager</th>
                    <th>Username Manager</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="item in managerData" :key="item.id">
                    <td>{{ item.kode_pegadaian }}</td>
                    <td>{{ item.lokasi_pegadaian }}</td>
                    <td>{{ item.nama }}</td>
                    <td>{{ item.username }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Footer -->
    <footer class="footer mt-4">
      <div class="d-sm-flex justify-content-center justify-content-sm-between">
        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">i-LockGada Pegadaian <a href="https://www.pegadaian.co.id/" target="_blank">https://www.pegadaian.co.id</a></span>
        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Safety and Trusty <i class="mdi mdi-lock text-danger"></i></span>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, nextTick } from 'vue'

import { collection, getDocs, query, where } from 'firebase/firestore'

definePageMeta({
  layout: 'direktur',
  middleware: ['auth', 'direktur']
})

const { $db } = useNuxtApp()
const { userName, userRole, userUsername } = useAuth()

const totalPegadaian = ref(0)
const managerData = ref([])

onMounted(async () => {
  try {
    const pegadaianSnap = await getDocs(collection($db, 'pegadaian'))
    totalPegadaian.value = pegadaianSnap.size

    const pegadaianDocs = {}
    pegadaianSnap.forEach(doc => { pegadaianDocs[doc.id] = doc.data() })

    const managersSnap = await getDocs(query(collection($db, 'users'), where('role', '==', 'manager')))
    const temp = []
    managersSnap.forEach(doc => {
      const m = doc.data()
      const p = pegadaianDocs[m.pegadaian_id] || {}
      temp.push({
        id: doc.id,
        kode_pegadaian: p.kode_pegadaian || '-',
        lokasi_pegadaian: p.lokasi_pegadaian || '-',
        nama: m.nama || '-',
        username: m.username || '-'
      })
    })
    managerData.value = temp

    // Init DataTable after Vue renders
    await nextTick()
    if (window.$ && !$.fn.DataTable.isDataTable('#direkturDashboardTable')) {
      $('#direkturDashboardTable').DataTable()
    }

  } catch (error) {
    console.error('Error loading data:', error)
  }
})

// Hapus DataTable saat komponen dilepas
onBeforeUnmount(() => {
  if (window.$ && $.fn.DataTable.isDataTable('#direkturDashboardTable')) {
    $('#direkturDashboardTable').DataTable().destroy()
  }
})
</script>

