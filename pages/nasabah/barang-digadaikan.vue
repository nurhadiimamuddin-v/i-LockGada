<template>
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between flex-wrap">
          <div class="d-flex align-items-end flex-wrap">
            <div class="mr-md-3 mr-xl-5">
              <h2>Data Barang yang digadaikan</h2>
              <p class="mb-md-0"> Data Barang yang digadaikan dengan menggunakan tabel </p>
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
              <p class="card-title mb-0">Data Barang yang digadaikan hari ini</p>
            </div>
            <div class="table-responsive">
              <table id="barangDigadaikanTable" class="display" style="width:100%">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Foto Barang</th>
                    <th>Tanggal Digadai</th>
                    <th>NIK Nasabah</th>
                    <th>Nama Nasabah</th>
                    <th>Deskripsi Barang</th>
                    <th>Bukti</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in gadaiList" :key="item.id">
                    <td>{{ index + 1 }}</td>
                    <td>
                      <template v-if="item.foto_barang">
                        <img :src="item.foto_barang" alt="Foto Barang" style="max-width: 70px; max-height: 70px; cursor: pointer; border-radius: 0;" @click="showFoto(item.foto_barang)">
                      </template>
                      <template v-else>
                        <span>Tidak ada foto</span>
                      </template>
                    </td>
                    <td>{{ item.tgl_digadai }}</td>
                    <td>{{ item.nik_rahin }}</td>
                    <td>{{ item.nama_rahin }}</td>
                    <td>{{ item.deskripsi_barang }}</td>
                    <td>
                      <a href="#" class="text-primary" style="text-decoration: none;" @click.prevent="cetakSurat(item.id)">
                        <i class="mdi mdi-file"></i> Cetak Surat
                      </a>
                    </td>
                    <td>
                      <button class="btn" style="font-size: 14px; color: white; padding: 8px 12px; background-color: #152453;" @click="hapusGadai(item)">
                        Hapus
                      </button>
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
    <div class="modal fade" id="modalFotoBarang" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Foto Barang Digadai</h5>
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
import { collection, getDocs, doc, deleteDoc, updateDoc, query, where } from 'firebase/firestore'

definePageMeta({
  layout: 'nasabah',
  middleware: ['auth', 'nasabah']
})

const { $db } = useNuxtApp()
const { user } = useAuth()

const gadaiList = ref([])
const selectedFoto = ref('')

const loadData = async () => {
  try {
    const pId = user.value?.pegadaian_id
    if (!pId) return

    const gadaiSnap = await getDocs(query(
      collection($db, 'barang_gadai'), 
      where('pegadaian_id', '==', pId),
      where('status_barang', '==', 'digadai')
    ))
    
    const rahinSnap = await getDocs(query(
      collection($db, 'rahin'),
      where('pegadaian_id', '==', pId)
    ))
    
    const rahinMap = {}
    rahinSnap.forEach(doc => {
      rahinMap[doc.id] = doc.data()
    })

    const temp = []
    gadaiSnap.forEach(doc => {
      const data = doc.data()
      const rahinData = rahinMap[data.rahin_id] || {}
      temp.push({
        id: doc.id,
        ...data,
        nama_rahin: rahinData.nama_rahin || 'Unknown',
        nik_rahin: rahinData.nik_rahin || 'Unknown'
      })
    })

    if (window.$ && $.fn.DataTable.isDataTable('#barangDigadaikanTable')) {
      $('#barangDigadaikanTable').DataTable().destroy()
    }
    
    gadaiList.value = temp

    setTimeout(() => {
      if (window.$ && !$.fn.DataTable.isDataTable('#barangDigadaikanTable')) {
        $('#barangDigadaikanTable').DataTable()
      }
    }, 100)
  } catch (error) {
    console.error('Error fetching data:', error)
  }
}

const showFoto = (fotoUrl) => {
  selectedFoto.value = fotoUrl
  const modal = new bootstrap.Modal(document.getElementById('modalFotoBarang'))
  modal.show()
}

const cetakSurat = (id) => {
  const item = gadaiList.value.find(i => i.id === id)
  if (item) {
    cetakSuratGadai(item)
  }
}

const hapusGadai = async (item) => {
  if (confirm('Yakin Akan Menghapus Data Ini?')) {
    try {
      // 1. Set locker back to belum_terisi
      if (item.locker_id) {
        const lRef = doc($db, 'lockers', item.locker_id)
        await updateDoc(lRef, { status: 'belum_terisi' })
      }
      
      // 2. Delete the gadai record
      await deleteDoc(doc($db, 'barang_gadai', item.id))
      
      await loadData()
      alert('Data Barang Gadai berhasil dihapus.')
    } catch (err) {
      console.error(err)
      alert('Gagal menghapus data gadai.')
    }
  }
}

onMounted(() => {
  loadData()
})

onBeforeUnmount(() => {
  if (window.$ && $.fn.DataTable.isDataTable('#barangDigadaikanTable')) {
    $('#barangDigadaikanTable').DataTable().destroy()
  }
})
</script>

