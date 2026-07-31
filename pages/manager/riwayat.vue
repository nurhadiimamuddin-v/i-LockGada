<template>
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between flex-wrap">
          <div class="d-flex align-items-end flex-wrap">
            <div class="mr-md-3 mr-xl-5">
              <h2>Data Riwayat Pegadaian Barang</h2>
              <p class="mb-md-0"> Data akan Memunculkan Riwayat barang yang digadaikan</p>
            </div>
            <div class="d-flex">
              <img src="/images/ty.png" alt="logo" style="height: 63px; margin-left: 480px;" class="d-none d-md-block">
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12 stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <p class="card-title mb-0">Data Riwayat Barang</p>
              <button class="btn btn-success" @click="downloadExcel">
                <span> Download Excel</span>
              </button>
            </div>
            <div class="table-responsive">
              <table id="riwayatTable" class="display" style="width:100%">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Pegawai</th>
                    <th>NIK Pegawai</th>
                    <th>Tanggal di Gadai</th>
                    <th>Foto Pegawai</th>
                    <th>Tanggal di Terima</th>
                    <th>Verifikasi Foto Pegawai</th>
                    <th>Kode Barang Gadai</th>
                    <th>Detail</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in riwayatList" :key="item.id">
                    <td>{{ index + 1 }}</td>
                    <td>{{ item.nama_pegawai }}</td>
                    <td>{{ item.nik_pegawai }}</td>
                    <td>{{ item.tgl_digadai }}</td>
                    <td>
                      <template v-if="item.foto_nasabah">
                        <img :src="item.foto_nasabah" alt="Foto Pegawai" style="max-width: 70px; max-height: 70px; cursor: pointer; border-radius: 0;" @click="showFoto(item.foto_nasabah, 'Foto Nasabah Ketika barang mau digadaikan')">
                      </template>
                      <template v-else>
                        <span>Tidak ada foto</span>
                      </template>
                    </td>
                    <td>{{ item.tgl_diterima || '-' }}</td>
                    <td>
                      <template v-if="item.finishing_foto_nasabah">
                        <img :src="item.finishing_foto_nasabah" alt="Verifikasi Foto" style="max-width: 70px; max-height: 70px; cursor: pointer; border-radius: 0;" @click="showFoto(item.finishing_foto_nasabah, 'Foto Pegawai yang telah di ambil')">
                      </template>
                      <template v-else>
                        <span>Tidak ada foto</span>
                      </template>
                    </td>
                    <td>{{ item.nik_rahin }}</td>
                    <td>
                      <span class="text-primary cursor-pointer" @click="alertDetail(item)">
                        <i class="mdi mdi-eye"></i> View
                      </span>
                    </td>
                    <td>
                      <button v-if="item.status_barang === 'digadai'" class="btn" style="font-size: 14px; color: white; padding: 8px 12px; background-color: #152453;">
                        Digadai
                      </button>
                      <button v-else-if="item.status_barang === 'diambil'" class="btn" style="font-size: 14px; color: white; padding: 8px 12px; background-color: #20a1ad;">
                        Diambil
                      </button>
                      <span v-else>{{ item.status_barang }}</span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Foto -->
    <div class="modal fade" id="modalFotoRiwayat" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ modalFotoTitle }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body text-center">
            <img :src="selectedFoto" alt="Foto" style="max-width: 100%; height: auto;">
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
import * as XLSX from 'xlsx'

definePageMeta({
  layout: 'manager',
  middleware: ['auth', 'manager']
})

const { $db } = useNuxtApp()
const { user } = useAuth()

const riwayatList = ref([])
const selectedFoto = ref('')
const modalFotoTitle = ref('')

const loadData = async () => {
  try {
    const pId = user.value?.pegadaian_id
    if (!pId) return

    // Load barang_gadai
    const gadaiSnap = await getDocs(query(collection($db, 'barang_gadai'), where('pegadaian_id', '==', pId)))
    
    // Load barang_diambil for matching
    const ambilSnap = await getDocs(query(collection($db, 'barang_diambil'), where('pegadaian_id', '==', pId)))
    const ambilMap = {}
    ambilSnap.forEach(doc => {
      const data = doc.data()
      ambilMap[data.barang_id] = data
    })

    // Load users (pegawai)
    const usersSnap = await getDocs(query(collection($db, 'users'), where('pegadaian_id', '==', pId)))
    const usersMap = {}
    usersSnap.forEach(doc => {
      usersMap[doc.id] = doc.data()
    })

    // Load rahin
    const rahinSnap = await getDocs(query(collection($db, 'rahin'), where('pegadaian_id', '==', pId)))
    const rahinMap = {}
    rahinSnap.forEach(doc => {
      rahinMap[doc.id] = doc.data()
    })

    const temp = []
    gadaiSnap.forEach(doc => {
      const data = doc.data()
      const bda = ambilMap[doc.id] || {}
      const pegawaiData = usersMap[data.nasabah_id] || {}
      const rahinData = rahinMap[data.rahin_id] || {}

      temp.push({
        id: doc.id,
        tgl_digadai: data.tgl_digadai,
        status_barang: data.status_barang,
        foto_nasabah: data.foto_nasabah,
        nama_pegawai: pegawaiData.nama || 'Unknown',
        nik_pegawai: pegawaiData.nik || 'Unknown',
        nik_rahin: rahinData.nik_rahin || 'Unknown',
        tgl_diterima: bda.tgl_diterima || '',
        finishing_foto_nasabah: bda.finishing_foto_nasabah || ''
      })
    })

    if (window.$ && $.fn.DataTable.isDataTable('#riwayatTable')) {
      $('#riwayatTable').DataTable().destroy()
    }
    
    riwayatList.value = temp

    await nextTick()
    if (window.$ && !$.fn.DataTable.isDataTable('#riwayatTable')) {
      $('#riwayatTable').DataTable()
    }
  } catch (error) {
    console.error('Error fetching data:', error)
  }
}

const showFoto = (fotoUrl, title) => {
  selectedFoto.value = fotoUrl
  modalFotoTitle.value = title
  const modal = new bootstrap.Modal(document.getElementById('modalFotoRiwayat'))
  modal.show()
}

const alertDetail = (item) => {
  alert(`Detail Riwayat:\nStatus: ${item.status_barang}\nKode Gadai (NIK Nasabah): ${item.nik_rahin}`)
}

const downloadExcel = () => {
  const wsData = [
    ['No', 'Nama Pegawai', 'NIK Pegawai', 'Tanggal Digadai', 'Tanggal Diterima', 'Kode Barang Gadai', 'Status']
  ]
  
  riwayatList.value.forEach((item, index) => {
    wsData.push([
      index + 1,
      item.nama_pegawai,
      item.nik_pegawai,
      item.tgl_digadai,
      item.tgl_diterima,
      item.nik_rahin,
      item.status_barang
    ])
  })
  
  const ws = XLSX.utils.aoa_to_sheet(wsData)
  const wb = XLSX.utils.book_new()
  XLSX.utils.book_append_sheet(wb, ws, 'Riwayat')
  
  XLSX.writeFile(wb, 'Laporan_Riwayat_Pegadaian.xlsx')
}

onMounted(() => {
  loadData()
})

onBeforeUnmount(() => {
  if (window.$ && $.fn.DataTable.isDataTable('#riwayatTable')) {
    $('#riwayatTable').DataTable().destroy()
  }
})
</script>

