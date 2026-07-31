<template>
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between flex-wrap">
          <div class="d-flex align-items-end flex-wrap">
            <div class="mr-md-3 mr-xl-5">
              <h2>Welcome Back to Smart Locker Pegadaian,</h2>
              <p class="mb-md-0"> Sistem penyimpanan pintar berbasis teknologi yang digunakan oleh PT Pegadaian (Persero) </p>
              <p class="mb-md-0">untuk memberikan layanan mandiri kepada nasabah dalam menyimpan, mengambil, atau menebus barang gadai</p>
            </div>
            <div class="d-flex">
              <img src="/images/ty.png" alt="logo" style="height: 83px; margin-left: 150px;" class="d-none d-md-block">
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body dashboard-tabs p-0">
            <ul class="nav nav-tabs px-4" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="overview-tab" data-bs-toggle="tab" href="#overview" role="tab">Profile Nasabah</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="sales-tab" data-bs-toggle="tab" href="#sales" role="tab">Info Pegadaian</a>
              </li>
            </ul>
            <div class="tab-content py-0 px-0">
              <!-- Profile Tab -->
              <div class="tab-pane fade show active" id="overview" role="tabpanel">
                <div class="d-flex flex-wrap justify-content-xl-between">
                  <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                    <i class="mdi mdi-account mr-3 icon-lg" style="color: #20a1ad;"></i>
                    <div class="d-flex flex-column justify-content-around">
                      <small class="mb-1 text-muted">Nama Pegawai</small>
                      <h5 class="mr-2 mb-0">{{ userName || '-' }}</h5>
                    </div>
                  </div>
                  <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                    <i class="mdi mdi-account-card-details mr-3 icon-lg text-success"></i>
                    <div class="d-flex flex-column justify-content-around">
                      <small class="mb-1 text-muted">NIK Pegawai</small>
                      <h5 class="mr-2 mb-0">{{ userUsername || '-' }}</h5>
                    </div>
                  </div>
                  <div class="d-flex py-3 border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                    <i class="mdi mdi-contact-mail mr-3 icon-lg" style="color: #152453;"></i>
                    <div class="d-flex flex-column justify-content-around">
                      <small class="mb-1 text-muted">Tempat, Tanggal Lahir</small>
                      <h5 class="mr-2 mb-0">{{ user.tempat_lahir || '-' }}, {{ formatDate(user.tanggal_lahir) }}</h5>
                    </div>
                  </div>
                  <div class="d-flex py-3 border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                    <i class="mdi mdi-account-key mr-3 icon-lg" style="color: #00ab4d;"></i>
                    <div class="d-flex flex-column justify-content-around">
                      <small class="mb-1 text-muted">Jabatan</small>
                      <h5 class="mr-2 mb-0">Pegawai (Nasabah)</h5>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Info Pegadaian Tab -->
              <div class="tab-pane fade" id="sales" role="tabpanel">
                <div class="d-flex flex-wrap justify-content-xl-between">
                  <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                    <i class="mdi mdi-google-physical-web mr-3 icon-lg text-success"></i>
                    <div class="d-flex flex-column justify-content-around">
                      <small class="mb-1 text-muted">Kode Pegadaian</small>
                      <h5 class="mr-2 mb-0">{{ pegadaianData.kode_pegadaian || '-' }}</h5>
                    </div>
                  </div>
                  <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                    <i class="mdi mdi-bank mr-3 icon-lg" style="color: #20a1ad;"></i>
                    <div class="d-flex flex-column justify-content-around">
                      <small class="mb-1 text-muted">Alamat Pegadaian</small>
                      <h5 class="mr-2 mb-0">{{ pegadaianData.lokasi_pegadaian || '-' }}</h5>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Data Table Lockers -->
    <div class="row">
      <div class="col-md-12 stretch-card">
        <div class="card">
          <div class="card-body">
            <p class="card-title">Data Status Locker di {{ pegadaianData.lokasi_pegadaian || 'Pegadaian' }}</p>
            <div class="table-responsive">
              <table id="nasabahDashboardTable" class="display" style="width:100%">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Kode Locker</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in lockerData" :key="item.id">
                    <td>{{ index + 1 }}</td>
                    <td>{{ item.kode_locker }}</td>
                    <td>
                      <span class="badge" :class="item.status === 'belum_terisi' ? 'bg-success text-white' : 'bg-danger text-white'">
                        {{ item.status }}
                      </span>
                    </td>
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
        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Smart Locker Pegadaian <a href="https://www.pegadaian.co.id/" target="_blank">https://www.pegadaian.co.id</a></span>
        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Safety and Trusty <i class="mdi mdi-lock text-danger"></i></span>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { doc, getDoc, collection, getDocs, query, where } from 'firebase/firestore'

definePageMeta({
  layout: 'nasabah',
  middleware: ['auth', 'nasabah']
})

const { $db } = useNuxtApp()
const { user, userName, userUsername } = useAuth()

const pegadaianData = ref({})
const lockerData = ref([])

const formatDate = (dateStr) => {
  if (!dateStr) return '-'
  const date = new Date(dateStr)
  return new Intl.DateTimeFormat('id-ID', { day: '2-digit', month: 'long', year: 'numeric' }).format(date)
}

onMounted(async () => {
  try {
    const pId = user.value?.pegadaian_id
    if (pId) {
      // Get Pegadaian info
      const pDoc = await getDoc(doc($db, 'pegadaian', pId))
      if (pDoc.exists()) {
        pegadaianData.value = pDoc.data()
      }

      // Get Lockers
      const lockersSnap = await getDocs(query(
        collection($db, 'lockers'), 
        where('pegadaian_id', '==', pId)
      ))
      
      const temp = []
      lockersSnap.forEach(doc => {
        temp.push({ id: doc.id, ...doc.data() })
      })
      lockerData.value = temp
    }

    // Init DataTable after Vue renders
    setTimeout(() => {
      if (window.$ && !$.fn.DataTable.isDataTable('#nasabahDashboardTable')) {
        $('#nasabahDashboardTable').DataTable()
      }
    }, 100)

  } catch (error) {
    console.error('Error loading data:', error)
  }
})

// Hapus DataTable saat komponen dilepas
onBeforeUnmount(() => {
  if (window.$ && $.fn.DataTable.isDataTable('#nasabahDashboardTable')) {
    $('#nasabahDashboardTable').DataTable().destroy()
  }
})
</script>
