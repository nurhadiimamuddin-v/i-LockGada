<template>
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between flex-wrap">
          <div class="d-flex align-items-end flex-wrap">
            <div class="mr-md-3 mr-xl-5">
              <h2>Data Locker</h2>
              <p class="mb-md-0"> Data akan Memunculkan Locker yang terisi dan belum terisi</p>
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
              <p class="card-title mb-0">Data Locker</p>
              <button class="btn btn-success" @click="openTambahModal">
                <span> Tambah</span>
              </button>
            </div>
            <div class="table-responsive">
              <table id="lockerTable" class="display" style="width:100%">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Kode Locker</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in lockerList" :key="item.id">
                    <td>{{ index + 1 }}</td>
                    <td>{{ item.kode_locker }}</td>
                    <td>
                      <span class="badge" :class="item.status === 'belum_terisi' ? 'bg-success text-white' : 'bg-danger text-white'">
                        {{ item.status === 'belum_terisi' ? 'Belum Terisi' : 'Sudah Terisi' }}
                      </span>
                    </td>
                    <td>
                      <button class="btn edit-btn" 
                         style="font-size: 14px; color: white; padding: 8px 12px; background-color: #20a1ad;" 
                         @click="openEditModal(item)">
                        Edit
                      </button>
                      <button class="btn" 
                              @click="deleteLocker(item.id)" 
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

    <!-- Modal Tambah Locker -->
    <div class="modal fade" id="modalTambahLocker" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Tambah Locker</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeTambahModal"></button>
          </div>
          <form @submit.prevent="addLocker">
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label">Kode Locker</label>
                <div class="input-group">
                  <span class="input-group-text">LCKR</span>
                  <input type="text" class="form-control" v-model="formTambah.kode" placeholder="00" pattern="[0-9]{2}" title="Masukkan 2 digit angka" required>
                </div>
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

    <!-- Modal Edit Locker -->
    <div class="modal fade" id="modalEditLocker" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Locker</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeEditModal"></button>
          </div>
          <form @submit.prevent="updateLocker">
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label">Kode Locker</label>
                <div class="input-group">
                  <span class="input-group-text">LCKR</span>
                  <input type="text" class="form-control" v-model="formEdit.kode" placeholder="00" pattern="[0-9]{2}" title="Masukkan 2 digit angka" required>
                </div>
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
import { ref, onMounted, onBeforeUnmount, nextTick } from 'vue'

import { collection, getDocs, addDoc, doc, updateDoc, deleteDoc, query, where } from 'firebase/firestore'

definePageMeta({
  layout: 'manager',
  middleware: ['auth', 'manager']
})

const { $db } = useNuxtApp()
const { user } = useAuth()

const lockerList = ref([])
const formTambah = ref({ kode: '' })
const formEdit = ref({ id: '', kode: '' })
const loadingTambah = ref(false)
const loadingEdit = ref(false)

const loadData = async () => {
  try {
    const pId = user.value?.pegadaian_id
    if (!pId) return

    const snap = await getDocs(query(
      collection($db, 'lockers'), 
      where('pegadaian_id', '==', pId)
    ))
    
    const temp = []
    snap.forEach(doc => {
      temp.push({ id: doc.id, ...doc.data() })
    })

    if (window.$ && $.fn.DataTable.isDataTable('#lockerTable')) {
      $('#lockerTable').DataTable().destroy()
    }
    
    lockerList.value = temp

    await nextTick()
    if (window.$ && !$.fn.DataTable.isDataTable('#lockerTable')) {
      $('#lockerTable').DataTable()
    }
  } catch (error) {
    console.error('Error fetching data:', error)
  }
}

onMounted(() => {
  loadData()
})

onBeforeUnmount(() => {
  if (window.$ && $.fn.DataTable.isDataTable('#lockerTable')) {
    $('#lockerTable').DataTable().destroy()
  }
})

// ADD
const openTambahModal = () => {
  formTambah.value = { kode: '' }
  const modal = new bootstrap.Modal(document.getElementById('modalTambahLocker'))
  modal.show()
}

const addLocker = async () => {
  loadingTambah.value = true
  try {
    const pId = user.value?.pegadaian_id
    const fullKode = 'LCKR' + formTambah.value.kode

    await addDoc(collection($db, 'lockers'), {
      kode_locker: fullKode,
      status: 'belum_terisi',
      pegadaian_id: pId
    })
    
    document.getElementById('closeTambahModal').click()
    await loadData()
    alert('Data Locker berhasil ditambahkan!')
  } catch (error) {
    console.error('Error adding document: ', error)
    alert('Gagal menambah data.')
  } finally {
    loadingTambah.value = false
  }
}

// EDIT
const openEditModal = (item) => {
  let kode = item.kode_locker || ''
  if (kode.startsWith('LCKR')) {
    kode = kode.substring(4)
  }
  
  formEdit.value = {
    id: item.id,
    kode: kode
  }
  
  const editModal = new bootstrap.Modal(document.getElementById('modalEditLocker'))
  editModal.show()
}

const updateLocker = async () => {
  loadingEdit.value = true
  try {
    const fullKode = 'LCKR' + formEdit.value.kode

    const ref = doc($db, 'lockers', formEdit.value.id)
    await updateDoc(ref, {
      kode_locker: fullKode
    })
    
    document.getElementById('closeEditModal').click()
    await loadData()
    alert('Data Locker berhasil diupdate!')
  } catch (error) {
    console.error('Error updating document: ', error)
    alert('Gagal mengupdate data.')
  } finally {
    loadingEdit.value = false
  }
}

// DELETE
const deleteLocker = async (id) => {
  if (confirm('Yakin Akan Menghapus Data Ini?')) {
    try {
      await deleteDoc(doc($db, 'lockers', id))
      await loadData()
      alert('Data Locker berhasil dihapus!')
    } catch (error) {
      console.error('Error deleting document: ', error)
      alert('Gagal menghapus data.')
    }
  }
}
</script>

