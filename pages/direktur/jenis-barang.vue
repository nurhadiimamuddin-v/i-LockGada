<template>
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between flex-wrap">
          <div class="d-flex align-items-end flex-wrap">
            <div class="mr-md-3 mr-xl-5">
              <h2>Data Jenis Barang</h2>
              <p class="mb-md-0"> Data berbentuk Tabel untuk Menampilkan Data Jenis Barang</p>
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
              <p class="card-title mb-0">Data Jenis Barang</p>
              <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahJenisBarang">
                <span> Tambah</span>
              </button>
            </div>
            <div class="table-responsive">
              <table id="jenisBarangTable" class="display" style="width:100%">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Kode Barang</th>
                    <th>Jenis Barang</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in jenisBarangList" :key="item.id">
                    <td>{{ index + 1 }}</td>
                    <td>{{ item.kode_barang }}</td>
                    <td>{{ item.jenis_barang }}</td>
                    <td>
                      <button class="btn edit-btn" 
                         style="font-size: 14px; color: white; padding: 8px 12px; background-color: #20a1ad;" 
                         @click="openEditModal(item)">
                        Edit
                      </button>
                      <button class="btn" 
                              @click="deleteJenisBarang(item.id)" 
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

    <!-- Modal Tambah Jenis Barang -->
    <div class="modal fade" id="modalTambahJenisBarang" tabindex="-1" aria-labelledby="modalTambahJenisBarangLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTambahJenisBarangLabel">Tambah Jenis Barang</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeTambahModal"></button>
          </div>
          <form @submit.prevent="addJenisBarang">
            <div class="modal-body">
              <div class="mb-3">
                <label for="kodeBarang" class="form-label">Kode Barang</label>
                <div class="input-group">
                  <span class="input-group-text" id="basic-addon1">KDBRG</span>
                  <input type="text" class="form-control" v-model="formTambah.kode" placeholder="0000" required>
                </div>
              </div>
              <div class="mb-3">
                <label for="jenisBarang" class="form-label">Jenis Barang</label>
                <input type="text" class="form-control" v-model="formTambah.jenis" placeholder="Jenis Barang" required>
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

    <!-- Modal Edit Jenis Barang -->
    <div class="modal fade" id="modalEditJenisBarang" tabindex="-1" aria-labelledby="modalEditJenisBarangLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalEditJenisBarangLabel">Edit Jenis Barang</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeEditModal"></button>
          </div>
          <form @submit.prevent="updateJenisBarang">
            <div class="modal-body">
              <div class="mb-3">
                <label for="editKodeBarang" class="form-label">Kode Barang</label>
                <div class="input-group">
                  <span class="input-group-text" id="basic-addon2">KDBRG</span>
                  <input type="text" class="form-control" v-model="formEdit.kode" placeholder="0000" required>
                </div>
              </div>
              <div class="mb-3">
                <label for="editJenisBarang" class="form-label">Jenis Barang</label>
                <input type="text" class="form-control" v-model="formEdit.jenis" placeholder="Jenis Barang" required>
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
        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Smart Locker Pegadaian <a href="https://www.pegadaian.co.id/" target="_blank">https://www.pegadaian.co.id</a></span>
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

const jenisBarangList = ref([])
const formTambah = ref({ kode: '', jenis: '' })
const formEdit = ref({ id: '', kode: '', jenis: '' })
const loadingTambah = ref(false)
const loadingEdit = ref(false)

const loadData = async () => {
  try {
    const snap = await getDocs(collection($db, 'jenis_barang'))
    const temp = []
    snap.forEach(doc => {
      temp.push({ id: doc.id, ...doc.data() })
    })
    
    if (window.$ && $.fn.DataTable.isDataTable('#jenisBarangTable')) {
      $('#jenisBarangTable').DataTable().destroy()
    }
    
    jenisBarangList.value = temp

    setTimeout(() => {
      if (window.$ && !$.fn.DataTable.isDataTable('#jenisBarangTable')) {
        $('#jenisBarangTable').DataTable()
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
  if (window.$ && $.fn.DataTable.isDataTable('#jenisBarangTable')) {
    $('#jenisBarangTable').DataTable().destroy()
  }
})

// ADD
const addJenisBarang = async () => {
  loadingTambah.value = true
  try {
    const fullKode = formTambah.value.kode.startsWith('KDBRG') 
      ? formTambah.value.kode 
      : 'KDBRG' + formTambah.value.kode

    await addDoc(collection($db, 'jenis_barang'), {
      kode_barang: fullKode,
      jenis_barang: formTambah.value.jenis
    })
    
    formTambah.value = { kode: '', jenis: '' }
    document.getElementById('closeTambahModal').click()
    await loadData()
    alert('Data Jenis Barang berhasil ditambahkan!')
  } catch (error) {
    console.error('Error adding document: ', error)
    alert('Gagal menambah data.')
  } finally {
    loadingTambah.value = false
  }
}

// EDIT
const openEditModal = (item) => {
  let kode = item.kode_barang || ''
  if (kode.startsWith('KDBRG')) {
    kode = kode.substring(5)
  }
  
  formEdit.value = {
    id: item.id,
    kode: kode,
    jenis: item.jenis_barang
  }
  
  const editModal = new bootstrap.Modal(document.getElementById('modalEditJenisBarang'))
  editModal.show()
}

const updateJenisBarang = async () => {
  loadingEdit.value = true
  try {
    const fullKode = formEdit.value.kode.startsWith('KDBRG') 
      ? formEdit.value.kode 
      : 'KDBRG' + formEdit.value.kode

    const ref = doc($db, 'jenis_barang', formEdit.value.id)
    await updateDoc(ref, {
      kode_barang: fullKode,
      jenis_barang: formEdit.value.jenis
    })
    
    document.getElementById('closeEditModal').click()
    await loadData()
    alert('Data Jenis Barang berhasil diupdate!')
  } catch (error) {
    console.error('Error updating document: ', error)
    alert('Gagal mengupdate data.')
  } finally {
    loadingEdit.value = false
  }
}

// DELETE
const deleteJenisBarang = async (id) => {
  if (confirm('Yakin Akan Menghapus Data Ini?')) {
    try {
      await deleteDoc(doc($db, 'jenis_barang', id))
      await loadData()
      alert('Data Jenis Barang berhasil dihapus!')
    } catch (error) {
      console.error('Error deleting document: ', error)
      alert('Gagal menghapus data.')
    }
  }
}
</script>
