<template>
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between flex-wrap">
          <div class="d-flex align-items-end flex-wrap">
            <div class="mr-md-3 mr-xl-5">
              <h2>Data Barang yang diambil</h2>
              <p class="mb-md-0"> Data Barang yang diambil dengan menggunakan tabel </p>
            </div>
            <div class="d-flex">
              <img src="/images/ty.png" alt="logo" style="height: 63px; margin-left: 530px;" class="d-none d-md-block">
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
              <p class="card-title mb-0">Data Barang yang diambil</p>
            </div>
            
            <div class="table-responsive">
              <table id="barangDiambilTable" class="display" style="width:100%">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Foto Barang</th>
                    <th>Tanggal Diterima</th>
                    <th>NIK Nasabah</th>
                    <th>NIK Pegawai</th>
                    <th>Bukti Barang diterima</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in ambilList" :key="item.id">
                    <td>{{ index + 1 }}</td>
                    <td>
                      <template v-if="item.finishing_foto_barang">
                        <img :src="item.finishing_foto_barang" alt="Foto Barang" style="max-width: 70px; max-height: 70px; cursor: pointer; border-radius: 0;" @click="showFoto(item.finishing_foto_barang)">
                      </template>
                      <template v-else>
                        <span>Tidak ada foto</span>
                      </template>
                    </td>
                    <td>{{ item.tgl_diterima }}</td>
                    <td>{{ item.nik_rahin }}</td>
                    <td>{{ item.nik_pegawai }}</td>
                    <td>
                      <a class="btn" href="#" @click.prevent="cetakSurat(item.id)" style="font-size: 15px; color: white; padding: 8px 12px; background-color: #20a1ad;">
                        <i class="mdi mdi-file"></i> Cetak Surat
                      </a>
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
    <div class="modal fade" id="modalFotoAmbil" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Foto Barang yang telah di ambil</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body text-center">
            <img :src="selectedFoto" alt="Foto Barang" style="max-width: 100%; height: auto;">
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
import { usePrint } from '~/composables/usePrint'

import { collection, getDocs, query, where } from 'firebase/firestore'

definePageMeta({
  layout: 'nasabah',
  middleware: ['auth', 'nasabah']
})

const { $db } = useNuxtApp()
const { user } = useAuth()

const ambilList = ref([])
const selectedFoto = ref('')

const { printSurat } = usePrint()

const loadData = async () => {
  try {
    const pId = user.value?.pegadaian_id
    if (!pId) return

    const ambilSnap = await getDocs(query(
      collection($db, 'barang_diambil'), 
      where('pegadaian_id', '==', pId)
    ))
    
    // We need nik_rahin (from rahin) and nik_pegawai (from nasabah/users)
    const rahinSnap = await getDocs(query(collection($db, 'rahin'), where('pegadaian_id', '==', pId)))
    const rahinMap = {}
    rahinSnap.forEach(doc => { rahinMap[doc.id] = doc.data() })
    
    const usersSnap = await getDocs(query(collection($db, 'users'), where('pegadaian_id', '==', pId)))
    const usersMap = {}
    usersSnap.forEach(doc => { usersMap[doc.id] = doc.data() })

    const temp = []
    ambilSnap.forEach(doc => {
      const data = doc.data()
      const rData = rahinMap[data.rahin_id] || {}
      const pData = usersMap[data.nasabah_id] || {}
      
      temp.push({
        id: doc.id,
        finishing_foto_barang: data.finishing_foto_barang,
        tgl_diterima: data.tgl_diterima,
        nik_rahin: rData.nik_rahin || 'Unknown',
        nik_pegawai: pData.nik || 'Unknown'
      })
    })

    if (window.$ && $.fn.DataTable.isDataTable('#barangDiambilTable')) {
      $('#barangDiambilTable').DataTable().destroy()
    }
    
    // Sort descending by date locally
    temp.sort((a, b) => new Date(b.tgl_diterima) - new Date(a.tgl_diterima))
    
    ambilList.value = temp

    await nextTick()
    if (window.$ && !$.fn.DataTable.isDataTable('#barangDiambilTable')) {
      $('#barangDiambilTable').DataTable()
    }
  } catch (error) {
    console.error('Error fetching data:', error)
  }
}

const showFoto = (fotoUrl) => {
  selectedFoto.value = fotoUrl
  const modal = new bootstrap.Modal(document.getElementById('modalFotoAmbil'))
  modal.show()
}

const cetakSurat = (id) => {
  const item = ambilList.value.find(i => i.id === id)
  if (item) {
    printSurat('Barang Diambil', {
      'NIK Nasabah': item.nik_rahin,
      'NIK Pegawai': item.nik_pegawai,
      'Tanggal Diterima': item.tgl_diterima
    })
  }
}

onMounted(() => {
  loadData()
})

onBeforeUnmount(() => {
  if (window.$ && $.fn.DataTable.isDataTable('#barangDiambilTable')) {
    $('#barangDiambilTable').DataTable().destroy()
  }
})
</script>

