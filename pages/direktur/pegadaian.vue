<template>
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between flex-wrap">
          <div class="d-flex align-items-end flex-wrap">
            <div class="mr-md-3 mr-xl-5">
              <h2>Data Pegadaian</h2>
              <p class="mb-md-0"> Data berbentuk Tabel untuk Menampilkan Data Pegadaian</p>
            </div>
            <div class="d-flex">
              <img src="/images/ty.png" alt="logo" style="height: 63px; margin-left: 550px;" class="d-none d-md-block">
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
              <p class="card-title mb-0">Data Pegadaian</p>
              <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahPegadaian">
                <span> Tambah</span>
              </button>
            </div>
            <div class="table-responsive">
              <table id="pegadaianTable" class="display" style="width:100%">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Kode Pegadaian</th>
                    <th>Lokasi Pegadaian</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in pegadaianList" :key="item.id">
                    <td>{{ index + 1 }}</td>
                    <td>{{ item.kode_pegadaian }}</td>
                    <td>{{ item.lokasi_pegadaian }}</td>
                    <td>
                      <button class="btn edit-btn" 
                         style="font-size: 14px; color: white; padding: 8px 12px; background-color: #20a1ad;" 
                         @click="openEditModal(item)">
                        Edit
                      </button>
                      <button class="btn" 
                              @click="deletePegadaian(item.id)" 
                              style="font-size: 14px; color: white; padding: 8px 12px; background-color: #152453; margin-left: 5px;">
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

    <!-- Modal Tambah Pegadaian -->
    <div class="modal fade" id="modalTambahPegadaian" tabindex="-1" aria-labelledby="modalTambahPegadaianLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTambahPegadaianLabel">Tambah Pegadaian</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeTambahModal"></button>
          </div>
          <form @submit.prevent="addPegadaian">
            <div class="modal-body">
              <div class="mb-3">
                <label for="kodePegadaian" class="form-label">Kode Pegadaian</label>
                <div class="input-group">
                  <span class="input-group-text" id="basic-addon1">PGD</span>
                  <input type="text" class="form-control" v-model="formTambah.kode" placeholder="0000" required>
                </div>
              </div>
              <div class="mb-3">
                <label for="lokasiPegadaian" class="form-label">Lokasi Pegadaian</label>
                <textarea class="form-control" v-model="formTambah.lokasi" placeholder="Jalan, Desa, Kecamatan, Kabupaten, Provinsi" rows="3" required></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-success" :disabled="loadingTambah">
                {{ loadingTambah ? 'Menyimpan...' : 'Simpan' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Modal Edit Pegadaian -->
    <div class="modal fade" id="modalEditPegadaian" tabindex="-1" aria-labelledby="modalEditPegadaianLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalEditPegadaianLabel">Edit Pegadaian</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeEditModal"></button>
          </div>
          <form @submit.prevent="updatePegadaian">
            <div class="modal-body">
              <div class="mb-3">
                <label for="editKodePegadaian" class="form-label">Kode Pegadaian</label>
                <div class="input-group">
                  <span class="input-group-text" id="basic-addon2">PGD</span>
                  <input type="text" class="form-control" v-model="formEdit.kode" placeholder="0000" required>
                </div>
              </div>
              <div class="mb-3">
                <label for="editLokasiPegadaian" class="form-label">Lokasi Pegadaian</label>
                <textarea class="form-control" v-model="formEdit.lokasi" placeholder="Jalan, Desa, Kecamatan, Kabupaten, Provinsi" rows="3" required></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-success" :disabled="loadingEdit">
                {{ loadingEdit ? 'Mengupdate...' : 'Update' }}
              </button>
            </div>
          </form>
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
import { collection, getDocs, addDoc, doc, updateDoc, deleteDoc } from 'firebase/firestore'

definePageMeta({
  layout: 'direktur',
  middleware: ['auth', 'direktur']
})

const { $db } = useNuxtApp()

const pegadaianList = ref([])
const formTambah = ref({ kode: '', lokasi: '' })
const formEdit = ref({ id: '', kode: '', lokasi: '' })
const loadingTambah = ref(false)
const loadingEdit = ref(false)

const loadData = async () => {
  try {
    const snap = await getDocs(collection($db, 'pegadaian'))
    const temp = []
    snap.forEach(doc => {
      temp.push({ id: doc.id, ...doc.data() })
    })
    
    // Hancurkan datatable lama sebelum re-render
    if (window.$ && $.fn.DataTable.isDataTable('#pegadaianTable')) {
      $('#pegadaianTable').DataTable().destroy()
    }
    
    pegadaianList.value = temp

    // Init datatable baru
    setTimeout(() => {
      if (window.$ && !$.fn.DataTable.isDataTable('#pegadaianTable')) {
        $('#pegadaianTable').DataTable()
      }
    }, 100)
  } catch (error) {
    console.error('Error fetching data:', error)
  }
}

onMounted(() => {
  loadData()
})

onBeforeUnmount(() => {
  if (window.$ && $.fn.DataTable.isDataTable('#pegadaianTable')) {
    $('#pegadaianTable').DataTable().destroy()
  }
})

// ADD
const addPegadaian = async () => {
  loadingTambah.value = true
  try {
    const fullKode = formTambah.value.kode.startsWith('PGD') 
      ? formTambah.value.kode 
      : 'PGD' + formTambah.value.kode

    await addDoc(collection($db, 'pegadaian'), {
      kode_pegadaian: fullKode,
      lokasi_pegadaian: formTambah.value.lokasi
    })
    
    formTambah.value = { kode: '', lokasi: '' }
    document.getElementById('closeTambahModal').click()
    await loadData()
    alert('Data Pegadaian berhasil ditambahkan!')
  } catch (error) {
    console.error('Error adding document: ', error)
    alert('Gagal menambah data.')
  } finally {
    loadingTambah.value = false
  }
}

// EDIT
const openEditModal = (item) => {
  let kode = item.kode_pegadaian || ''
  if (kode.startsWith('PGD')) {
    kode = kode.substring(3)
  }
  
  formEdit.value = {
    id: item.id,
    kode: kode,
    lokasi: item.lokasi_pegadaian
  }
  
  // Show modal (using Bootstrap JS via window)
  const editModal = new bootstrap.Modal(document.getElementById('modalEditPegadaian'))
  editModal.show()
}

const updatePegadaian = async () => {
  loadingEdit.value = true
  try {
    const fullKode = formEdit.value.kode.startsWith('PGD') 
      ? formEdit.value.kode 
      : 'PGD' + formEdit.value.kode

    const ref = doc($db, 'pegadaian', formEdit.value.id)
    await updateDoc(ref, {
      kode_pegadaian: fullKode,
      lokasi_pegadaian: formEdit.value.lokasi
    })
    
    document.getElementById('closeEditModal').click()
    await loadData()
    alert('Data Pegadaian berhasil diupdate!')
  } catch (error) {
    console.error('Error updating document: ', error)
    alert('Gagal mengupdate data.')
  } finally {
    loadingEdit.value = false
  }
}

// DELETE
const deletePegadaian = async (id) => {
  if (confirm('Yakin Akan Menghapus Data Ini?')) {
    try {
      await deleteDoc(doc($db, 'pegadaian', id))
      await loadData()
      alert('Data Pegadaian berhasil dihapus!')
    } catch (error) {
      console.error('Error deleting document: ', error)
      alert('Gagal menghapus data.')
    }
  }
}
</script>

