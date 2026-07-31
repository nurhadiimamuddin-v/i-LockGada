<template>
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between flex-wrap">
          <div class="d-flex align-items-end flex-wrap">
            <div class="mr-md-3 mr-xl-5">
              <h2>Data Nasabah</h2>
              <p class="mb-md-0"> Data Nasabah (Orang yang menggadaikan) dengan menggunakan tabel </p>
            </div>
            <div class="d-flex">
              <img src="/images/ty.png" alt="logo" style="height: 63px; margin-left: 380px;" class="d-none d-md-block">
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
              <p class="card-title mb-0">Data Nasabah</p>
              <button class="btn btn-success" @click="openTambahModal">
                <span> Tambah</span>
              </button>
            </div>
            <div class="table-responsive">
              <table id="rahinTable" class="display" style="width:100%">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Nasabah</th>
                    <th>NIK Nasabah</th>
                    <th>Nomor Whatsapp</th>
                    <th>Email</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in rahinList" :key="item.id">
                    <td>{{ index + 1 }}</td>
                    <td>{{ item.nama_rahin }}</td>
                    <td>{{ item.nik_rahin }}</td>
                    <td>{{ item.no_whatsapp }}</td>
                    <td>{{ item.email }}</td>
                    <td>
                      <button class="btn edit-btn" 
                         style="font-size: 14px; color: white; padding: 8px 12px; background-color: #20a1ad;" 
                         @click="openEditModal(item)">
                        Edit
                      </button>
                      <button class="btn" 
                              @click="deleteRahin(item.id)" 
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

    <!-- Modal Tambah Rahin -->
    <div class="modal fade" id="modalTambahRahin" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Tambah Nasabah</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeTambahModal"></button>
          </div>
          <form @submit.prevent="addRahin">
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label">Nama Nasabah</label>
                <input type="text" class="form-control" v-model="formTambah.nama_rahin" placeholder="Masukkan Nama Nasabah" required>
              </div>
              <div class="mb-3">
                <label class="form-label">NIK</label>
                <input type="text" class="form-control" v-model="formTambah.nik_rahin" placeholder="Masukkan Nomor Induk Kependudukan" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Nomer Whatsapp</label>
                <input type="text" class="form-control" v-model="formTambah.no_whatsapp" placeholder="Masukkan Nomer Whatsapp" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Email</label>
                <div class="input-group">
                  <input type="text" class="form-control" v-model="formTambah.email_prefix" placeholder="Email" required>
                  <span class="input-group-text">@gmail.com</span>
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

    <!-- Modal Edit Rahin -->
    <div class="modal fade" id="modalEditRahin" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Nasabah</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeEditModal"></button>
          </div>
          <form @submit.prevent="updateRahin">
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label">Nama Nasabah</label>
                <input type="text" class="form-control" v-model="formEdit.nama_rahin" placeholder="Masukkan Nama Nasabah" required>
              </div>
              <div class="mb-3">
                <label class="form-label">NIK</label>
                <input type="text" class="form-control" v-model="formEdit.nik_rahin" placeholder="Masukkan Nomor Induk Kependudukan" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Nomer Whatsapp</label>
                <input type="text" class="form-control" v-model="formEdit.no_whatsapp" placeholder="Masukkan Nomer Whatsapp" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Email</label>
                <div class="input-group">
                  <input type="text" class="form-control" v-model="formEdit.email_prefix" placeholder="Email" required>
                  <span class="input-group-text">@gmail.com</span>
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

const rahinList = ref([])
const formTambah = ref({ nama_rahin: '', nik_rahin: '', no_whatsapp: '', email_prefix: '' })
const formEdit = ref({ id: '', nama_rahin: '', nik_rahin: '', no_whatsapp: '', email_prefix: '' })
const loadingTambah = ref(false)
const loadingEdit = ref(false)

const loadData = async () => {
  try {
    const pId = user.value?.pegadaian_id
    if (!pId) return

    const snap = await getDocs(query(
      collection($db, 'rahin'), 
      where('pegadaian_id', '==', pId)
    ))
    
    const temp = []
    snap.forEach(doc => {
      temp.push({ id: doc.id, ...doc.data() })
    })

    if (window.$ && $.fn.DataTable.isDataTable('#rahinTable')) {
      $('#rahinTable').DataTable().destroy()
    }
    
    rahinList.value = temp

    await nextTick()
    if (window.$ && !$.fn.DataTable.isDataTable('#rahinTable')) {
      $('#rahinTable').DataTable()
    }
  } catch (error) {
    console.error('Error fetching data:', error)
  }
}

onMounted(() => {
  loadData()
})

onBeforeUnmount(() => {
  if (window.$ && $.fn.DataTable.isDataTable('#rahinTable')) {
    $('#rahinTable').DataTable().destroy()
  }
})

// ADD
const openTambahModal = () => {
  formTambah.value = { nama_rahin: '', nik_rahin: '', no_whatsapp: '', email_prefix: '' }
  const modal = new bootstrap.Modal(document.getElementById('modalTambahRahin'))
  modal.show()
}

const addRahin = async () => {
  loadingTambah.value = true
  try {
    const pId = user.value?.pegadaian_id
    await addDoc(collection($db, 'rahin'), {
      nama_rahin: formTambah.value.nama_rahin,
      nik_rahin: formTambah.value.nik_rahin,
      no_whatsapp: formTambah.value.no_whatsapp,
      email: formTambah.value.email_prefix + '@gmail.com',
      pegadaian_id: pId
    })
    
    document.getElementById('closeTambahModal').click()
    await loadData()
    alert('Data Nasabah berhasil ditambahkan!')
  } catch (error) {
    console.error('Error adding document: ', error)
    alert('Gagal menambah data.')
  } finally {
    loadingTambah.value = false
  }
}

// EDIT
const openEditModal = (item) => {
  let emailPrefix = item.email || ''
  if (emailPrefix.endsWith('@gmail.com')) {
    emailPrefix = emailPrefix.slice(0, -10)
  }
  
  formEdit.value = {
    id: item.id,
    nama_rahin: item.nama_rahin,
    nik_rahin: item.nik_rahin,
    no_whatsapp: item.no_whatsapp,
    email_prefix: emailPrefix
  }
  
  const editModal = new bootstrap.Modal(document.getElementById('modalEditRahin'))
  editModal.show()
}

const updateRahin = async () => {
  loadingEdit.value = true
  try {
    const ref = doc($db, 'rahin', formEdit.value.id)
    await updateDoc(ref, {
      nama_rahin: formEdit.value.nama_rahin,
      nik_rahin: formEdit.value.nik_rahin,
      no_whatsapp: formEdit.value.no_whatsapp,
      email: formEdit.value.email_prefix + '@gmail.com'
    })
    
    document.getElementById('closeEditModal').click()
    await loadData()
    alert('Data Nasabah berhasil diupdate!')
  } catch (error) {
    console.error('Error updating document: ', error)
    alert('Gagal mengupdate data.')
  } finally {
    loadingEdit.value = false
  }
}

// DELETE
const deleteRahin = async (id) => {
  if (confirm('Yakin Akan Menghapus Data Ini?')) {
    try {
      await deleteDoc(doc($db, 'rahin', id))
      await loadData()
      alert('Data Nasabah berhasil dihapus!')
    } catch (error) {
      console.error('Error deleting document: ', error)
      alert('Gagal menghapus data.')
    }
  }
}
</script>

