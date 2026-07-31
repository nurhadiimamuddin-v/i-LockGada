<template>
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between flex-wrap">
          <div class="d-flex align-items-end flex-wrap">
            <div class="mr-md-3 mr-xl-5">
              <h2>Data Pegawai Pegadaian</h2>
              <p class="mb-md-0"> Data akan Memunculkan Pegawai Pegadaian yang sudah terdaftar</p>
            </div>
            <div class="d-flex">
              <img src="/images/ty.png" alt="logo" style="height: 63px; margin-left: 500px;" class="d-none d-md-block">
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
              <p class="card-title mb-0">Data Pegawai Pegadaian</p>
              <button class="btn btn-success" @click="openTambahModal">
                <span> Tambah</span>
              </button>
            </div>
            <div class="table-responsive">
              <table id="nasabahTable" class="display" style="width:100%">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Pegawai</th>
                    <th>NIK Pegawai</th>
                    <th>Tempat Lahir</th>
                    <th>Tanggal Lahir</th>
                    <th>Password</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in nasabahList" :key="item.id">
                    <td>{{ index + 1 }}</td>
                    <td>{{ item.nama }}</td>
                    <td>{{ item.nik }}</td>
                    <td>{{ item.tempat_lahir }}</td>
                    <td>{{ formatDate(item.tanggal_lahir) }}</td>
                    <td>
                      <!-- Password visible logic or hidden -->
                      <span class="text-primary cursor-pointer" @click="alertPass(item.password)">
                        <i class="mdi mdi-eye"></i> View
                      </span>
                    </td>
                    <td>
                      <button class="btn edit-btn" 
                         style="font-size: 14px; color: white; padding: 8px 12px; background-color: #20a1ad;" 
                         @click="openEditModal(item)">
                        Edit
                      </button>
                      <button class="btn" 
                              @click="deleteNasabah(item.id)" 
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

    <!-- Modal Tambah Nasabah -->
    <div class="modal fade" id="modalTambahNasabah" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Tambah Pegawai Pegadaian</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeTambahModal"></button>
          </div>
          <form @submit.prevent="addNasabah">
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label">Nama Pegawai</label>
                <input type="text" class="form-control" v-model="formTambah.nama" placeholder="Masukkan Nama Pegawai" required>
              </div>
              <div class="mb-3">
                <label class="form-label">NIK</label>
                <input type="text" class="form-control" v-model="formTambah.nik" placeholder="Masukkan NIK" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Tempat Lahir</label>
                <input type="text" class="form-control" v-model="formTambah.tempat_lahir" placeholder="Masukkan Tempat Lahir" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Tanggal Lahir</label>
                <input type="date" class="form-control" v-model="formTambah.tanggal_lahir" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" v-model="formTambah.password" placeholder="Masukkan Password" required>
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

    <!-- Modal Edit Nasabah -->
    <div class="modal fade" id="modalEditNasabah" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Pegawai Pegadaian</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeEditModal"></button>
          </div>
          <form @submit.prevent="updateNasabah">
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label">Nama Pegawai</label>
                <input type="text" class="form-control" v-model="formEdit.nama" placeholder="Masukkan Nama Pegawai" required>
              </div>
              <div class="mb-3">
                <label class="form-label">NIK</label>
                <input type="text" class="form-control" v-model="formEdit.nik" placeholder="Masukkan NIK" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Tempat Lahir</label>
                <input type="text" class="form-control" v-model="formEdit.tempat_lahir" placeholder="Masukkan Tempat Lahir" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Tanggal Lahir</label>
                <input type="date" class="form-control" v-model="formEdit.tanggal_lahir" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Password (Kosongkan jika tidak diubah)</label>
                <input type="password" class="form-control" v-model="formEdit.password" placeholder="Masukkan Password Baru">
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
import { collection, getDocs, addDoc, doc, updateDoc, deleteDoc, query, where } from 'firebase/firestore'

definePageMeta({
  layout: 'manager',
  middleware: ['auth', 'manager']
})

const { $db } = useNuxtApp()
const { user } = useAuth()

const nasabahList = ref([])
const formTambah = ref({ nama: '', nik: '', tempat_lahir: '', tanggal_lahir: '', password: '' })
const formEdit = ref({ id: '', nama: '', nik: '', tempat_lahir: '', tanggal_lahir: '', password: '' })
const loadingTambah = ref(false)
const loadingEdit = ref(false)

const formatDate = (dateStr) => {
  if (!dateStr) return '-'
  return dateStr
}

const alertPass = (pass) => {
  alert(`Password: ${pass}`)
}

const loadData = async () => {
  try {
    const pId = user.value?.pegadaian_id
    if (!pId) return

    const snap = await getDocs(query(
      collection($db, 'users'), 
      where('role', '==', 'nasabah'),
      where('pegadaian_id', '==', pId)
    ))
    
    const temp = []
    snap.forEach(doc => {
      temp.push({ id: doc.id, ...doc.data() })
    })

    if (window.$ && $.fn.DataTable.isDataTable('#nasabahTable')) {
      $('#nasabahTable').DataTable().destroy()
    }
    
    nasabahList.value = temp

    setTimeout(() => {
      if (window.$ && !$.fn.DataTable.isDataTable('#nasabahTable')) {
        $('#nasabahTable').DataTable()
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
  if (window.$ && $.fn.DataTable.isDataTable('#nasabahTable')) {
    $('#nasabahTable').DataTable().destroy()
  }
})

// ADD
const openTambahModal = () => {
  formTambah.value = { nama: '', nik: '', tempat_lahir: '', tanggal_lahir: '', password: '' }
  const modal = new bootstrap.Modal(document.getElementById('modalTambahNasabah'))
  modal.show()
}

const addNasabah = async () => {
  loadingTambah.value = true
  try {
    const pId = user.value?.pegadaian_id
    await addDoc(collection($db, 'users'), {
      role: 'nasabah',
      nama: formTambah.value.nama,
      nik: formTambah.value.nik,
      tempat_lahir: formTambah.value.tempat_lahir,
      tanggal_lahir: formTambah.value.tanggal_lahir,
      password: formTambah.value.password,
      pegadaian_id: pId
    })
    
    document.getElementById('closeTambahModal').click()
    await loadData()
    alert('Data Pegawai Pegadaian berhasil ditambahkan!')
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
    nik: item.nik,
    tempat_lahir: item.tempat_lahir,
    tanggal_lahir: item.tanggal_lahir,
    password: ''
  }
  
  const editModal = new bootstrap.Modal(document.getElementById('modalEditNasabah'))
  editModal.show()
}

const updateNasabah = async () => {
  loadingEdit.value = true
  try {
    const updateData = {
      nama: formEdit.value.nama,
      nik: formEdit.value.nik,
      tempat_lahir: formEdit.value.tempat_lahir,
      tanggal_lahir: formEdit.value.tanggal_lahir
    }
    if (formEdit.value.password) {
      updateData.password = formEdit.value.password
    }

    const ref = doc($db, 'users', formEdit.value.id)
    await updateDoc(ref, updateData)
    
    document.getElementById('closeEditModal').click()
    await loadData()
    alert('Data Pegawai Pegadaian berhasil diupdate!')
  } catch (error) {
    console.error('Error updating document: ', error)
    alert('Gagal mengupdate data.')
  } finally {
    loadingEdit.value = false
  }
}

// DELETE
const deleteNasabah = async (id) => {
  if (confirm('Yakin Akan Menghapus Data Ini?')) {
    try {
      await deleteDoc(doc($db, 'users', id))
      await loadData()
      alert('Data Pegawai Pegadaian berhasil dihapus!')
    } catch (error) {
      console.error('Error deleting document: ', error)
      alert('Gagal menghapus data.')
    }
  }
}
</script>

