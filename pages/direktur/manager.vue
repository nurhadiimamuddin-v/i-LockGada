<template>
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between flex-wrap">
          <div class="d-flex align-items-end flex-wrap">
            <div class="mr-md-3 mr-xl-5">
              <h2>Data Manager</h2>
              <p class="mb-md-0"> Data berbentuk Tabel untuk Menampilkan Data Manager</p>
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
              <p class="card-title mb-0">Data Manager</p>
              <button class="btn btn-success" @click="openTambahModal">
                <span> Tambah</span>
              </button>
            </div>
            <div class="table-responsive">
              <table id="managerTable" class="display" style="width:100%">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Manager</th>
                    <th>Kode Pegadaian</th>
                    <th>Lokasi Pegadaian</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in managerList" :key="item.id">
                    <td>{{ index + 1 }}</td>
                    <td>{{ item.nama }}</td>
                    <td>{{ item.kode_pegadaian }}</td>
                    <td>{{ item.lokasi_pegadaian }}</td>
                    <td>{{ item.username }}</td>
                    <td>{{ item.password }}</td>
                    <td>
                      <button class="btn edit-btn" 
                         style="font-size: 14px; color: white; padding: 8px 12px; background-color: #20a1ad;" 
                         @click="openEditModal(item)">
                        Edit
                      </button>
                      <button class="btn" 
                              @click="deleteManager(item.id)" 
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

    <!-- Modal Tambah Manager -->
    <div class="modal fade" id="modalTambahManager" tabindex="-1" aria-labelledby="modalTambahManagerLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTambahManagerLabel">Tambah Manager</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeTambahModal"></button>
          </div>
          <form @submit.prevent="addManager">
            <div class="modal-body">
              <div class="mb-3">
                <label for="nama" class="form-label">Nama Manager</label>
                <input type="text" class="form-control" v-model="formTambah.nama" placeholder="Masukkan Nama Manager" required>
              </div>
              <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" v-model="formTambah.username" placeholder="Masukkan Username" required>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" v-model="formTambah.password" placeholder="Masukkan Password" required>
              </div>
              <div class="mb-3">
                <label for="kodePegadaian" class="form-label">Kode Pegadaian</label>
                <select class="form-select" v-model="formTambah.pegadaian_id" style="font-size: 13px;" required>
                  <option value="" disabled>--- PILIH ---</option>
                  <option v-for="p in availablePegadaian" :key="p.id" :value="p.id">
                    {{ p.kode_pegadaian }} - {{ p.lokasi_pegadaian }}
                  </option>
                </select>
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

    <!-- Modal Edit Manager -->
    <div class="modal fade" id="modalEditManager" tabindex="-1" aria-labelledby="modalEditManagerLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalEditManagerLabel">Edit Manager</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeEditModal"></button>
          </div>
          <form @submit.prevent="updateManager">
            <div class="modal-body">
              <div class="mb-3">
                <label for="editNama" class="form-label">Nama Manager</label>
                <input type="text" class="form-control" v-model="formEdit.nama" placeholder="Masukkan Nama Manager" required>
              </div>
              <div class="mb-3">
                <label for="editUsername" class="form-label">Username</label>
                <input type="text" class="form-control" v-model="formEdit.username" placeholder="Masukkan Username" required>
              </div>
              <div class="mb-3">
                <label for="editPassword" class="form-label">Password (Kosongkan jika tidak diubah)</label>
                <input type="password" class="form-control" v-model="formEdit.password" placeholder="Masukkan Password Baru">
              </div>
              <div class="mb-3">
                <label for="editKodePegadaian" class="form-label">Kode Pegadaian</label>
                <select class="form-select" v-model="formEdit.pegadaian_id" style="font-size: 13px;" required>
                  <option value="" disabled>--- PILIH ---</option>
                  <option v-for="p in getAvailablePegadaianForEdit(formEdit.pegadaian_id)" :key="p.id" :value="p.id">
                    {{ p.kode_pegadaian }} - {{ p.lokasi_pegadaian }}
                  </option>
                </select>
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
import { collection, getDocs, addDoc, doc, updateDoc, deleteDoc, query, where } from 'firebase/firestore'

definePageMeta({
  layout: 'direktur',
  middleware: ['auth', 'direktur']
})

const { $db } = useNuxtApp()

const managerList = ref([])
const pegadaianList = ref([])
const formTambah = ref({ nama: '', username: '', password: '', pegadaian_id: '' })
const formEdit = ref({ id: '', nama: '', username: '', password: '', pegadaian_id: '' })
const loadingTambah = ref(false)
const loadingEdit = ref(false)

const loadData = async () => {
  try {
    // 1. Get Pegadaian
    const pSnap = await getDocs(collection($db, 'pegadaian'))
    const pTemp = []
    const pMap = {}
    pSnap.forEach(doc => {
      const data = { id: doc.id, ...doc.data() }
      pTemp.push(data)
      pMap[doc.id] = data
    })
    pegadaianList.value = pTemp

    // 2. Get Managers
    const mSnap = await getDocs(query(collection($db, 'users'), where('role', '==', 'manager')))
    const mTemp = []
    mSnap.forEach(doc => {
      const data = doc.data()
      const p = pMap[data.pegadaian_id] || {}
      mTemp.push({
        id: doc.id,
        ...data,
        kode_pegadaian: p.kode_pegadaian || '-',
        lokasi_pegadaian: p.lokasi_pegadaian || '-'
      })
    })

    if (window.$ && $.fn.DataTable.isDataTable('#managerTable')) {
      $('#managerTable').DataTable().destroy()
    }
    
    managerList.value = mTemp

    setTimeout(() => {
      if (window.$ && !$.fn.DataTable.isDataTable('#managerTable')) {
        $('#managerTable').DataTable()
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
  if (window.$ && $.fn.DataTable.isDataTable('#managerTable')) {
    $('#managerTable').DataTable().destroy()
  }
})

const getUsedPegadaianIds = () => {
  return managerList.value.map(m => m.pegadaian_id).filter(id => id)
}

const availablePegadaian = computed(() => {
  const usedIds = getUsedPegadaianIds()
  return pegadaianList.value.filter(p => !usedIds.includes(p.id))
})

const getAvailablePegadaianForEdit = (currentPegadaianId) => {
  const usedIds = getUsedPegadaianIds()
  return pegadaianList.value.filter(p => !usedIds.includes(p.id) || p.id === currentPegadaianId)
}

// ADD
const openTambahModal = () => {
  formTambah.value = { nama: '', username: '', password: '', pegadaian_id: '' }
  const modal = new bootstrap.Modal(document.getElementById('modalTambahManager'))
  modal.show()
}

const addManager = async () => {
  loadingTambah.value = true
  try {
    await addDoc(collection($db, 'users'), {
      role: 'manager',
      nama: formTambah.value.nama,
      username: formTambah.value.username,
      password: formTambah.value.password,
      pegadaian_id: formTambah.value.pegadaian_id
    })
    
    document.getElementById('closeTambahModal').click()
    await loadData()
    alert('Data Manager berhasil ditambahkan!')
  } catch (error) {
    console.error('Error adding document: ', error)
    alert('Gagal menambah data.')
  } finally {
    loadingTambah.value = false
  }
}

// EDIT
const openEditModal = (item) => {
  formEdit.value = {
    id: item.id,
    nama: item.nama,
    username: item.username,
    password: item.password,
    pegadaian_id: item.pegadaian_id
  }
  
  const editModal = new bootstrap.Modal(document.getElementById('modalEditManager'))
  editModal.show()
}

const updateManager = async () => {
  loadingEdit.value = true
  try {
    const updateData = {
      nama: formEdit.value.nama,
      username: formEdit.value.username,
      pegadaian_id: formEdit.value.pegadaian_id
    }
    if (formEdit.value.password) {
      updateData.password = formEdit.value.password
    }

    const ref = doc($db, 'users', formEdit.value.id)
    await updateDoc(ref, updateData)
    
    document.getElementById('closeEditModal').click()
    await loadData()
    alert('Data Manager berhasil diupdate!')
  } catch (error) {
    console.error('Error updating document: ', error)
    alert('Gagal mengupdate data.')
  } finally {
    loadingEdit.value = false
  }
}

// DELETE
const deleteManager = async (id) => {
  if (confirm('Yakin Akan Menghapus Data Ini?')) {
    try {
      await deleteDoc(doc($db, 'users', id))
      await loadData()
      alert('Data Manager berhasil dihapus!')
    } catch (error) {
      console.error('Error deleting document: ', error)
      alert('Gagal menghapus data.')
    }
  }
}
</script>
