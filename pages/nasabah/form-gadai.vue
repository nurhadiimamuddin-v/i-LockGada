<template>
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between flex-wrap">
          <div class="d-flex align-items-end flex-wrap">
            <div class="mr-md-3 mr-xl-5">
              <h2>Data Barang yang akan digadaikan</h2>
              <p class="mb-md-0">Silahkan Untuk mencari <strong>locker yang kosong</strong> terlebih dahulu</p>
            </div>
            <div class="d-flex">
              <img src="/images/ty.png" alt="logo" style="height: 63px; margin-left: 450px;" class="d-none d-md-block">
            </div>
          </div>
        </div>   
      </div>
    </div>
        
    <div class="row">
      <div class="col-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">DATA LOCKER YANG KOSONG</h4>
            
            <form class="forms-sample d-flex align-items-center" @submit.prevent="searchLocker">
              <div class="form-group flex-grow-1">
                <label for="locker-search">Berikut Data Locker yang Belum Terisi:</label>
                <select class="form-control" v-model="selectedLockerCode" required>
                  <option value="" disabled>Pilih Locker</option>
                  <option v-for="locker in availableLockers" :key="locker.id" :value="locker.kode_locker">
                    {{ locker.kode_locker }}
                  </option>
                </select>  
              </div>
              <div class="form-group ms-3" style="margin-left: 1rem;">
                <button type="submit" class="btn mt-4" style="color: white; background-color: #20a1ad;">Search</button>
              </div>
            </form>

            <div v-if="searchError" class="alert alert-danger mt-3">
              Locker tidak ditemukan atau tidak tersedia. Silakan cari dengan kode yang lain.
            </div>
            
            <div v-if="activeLocker" class="alert alert-success mt-3">
              Locker tersedia: <strong>{{ activeLocker.kode_locker }}</strong>
            </div>
          </div>
        </div>
      </div>
    </div>
            
    <div class="row" v-if="activeLocker">
      <div class="col-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">FORM BARANG YANG AKAN DIGADAIKAN</h4>
            <p class="card-description">Silahkan isi form berikut untuk menggadaikan barang</p>
            
            <form class="forms-sample" @submit.prevent="submitGadai">
              <!-- Form Section 1 -->
              <div class="form-group">
                <label>Nama Nasabah</label>
                <select class="form-control" v-model="formData.rahin_id" @change="onRahinChange" required>
                  <option value="">Pilih Nama Nasabah</option>
                  <option v-for="rahin in availableRahins" :key="rahin.id" :value="rahin.id">
                    {{ rahin.nama_rahin }}
                  </option>
                </select>
              </div>

              <div class="form-group">
                <label>NIK Nasabah <span style="color: red;">(Kode barang yang digadaikan)</span></label>
                <input type="text" class="form-control" v-model="formData.nik_rahin" placeholder="NIK" readonly required>
              </div>

              <div class="form-group">
                <label>Nomor Whatsapp</label>
                <input type="text" class="form-control" v-model="formData.no_whatsapp" placeholder="Nomor Whatsapp" readonly required>
              </div>

              <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" v-model="formData.email" placeholder="Email" readonly required>
              </div>

              <div v-if="formData.rahin_id">
                <div style="border-top: 15px solid #20a1ad; margin: 50px 0;"></div>

                <!-- Form Section 2 -->
                <div class="form-group">
                  <label>Nama Staff Pegadaian</label>
                  <input type="text" class="form-control" :value="user?.nama" readonly required>
                </div>
                <div class="form-group">
                  <label>Kode Pegadaian</label>
                  <input type="text" class="form-control" :value="user?.pegadaian_id" readonly required>
                </div>
                
                <div class="form-group">
                  <label>Jenis Barang</label>
                  <select class="form-control" v-model="formData.jenis_id" required>
                    <option value="">Pilih Jenis Barang</option>
                    <option v-for="jenis in jenisBarangList" :key="jenis.id" :value="jenis.id">
                      {{ jenis.jenis_barang }}
                    </option>
                  </select>
                </div>
                
                <div class="form-group">
                  <label>Deskripsi Barang</label>
                  <textarea class="form-control" v-model="formData.deskripsi_barang" rows="3" placeholder="Deskripsikan barang secara detail" required></textarea>
                </div>
                
                <div class="form-group">
                  <label>Tanggal yang digadaikan</label>
                  <input type="text" class="form-control" :value="currentDate" readonly>
                </div>

                <hr>

                <!-- Form Section 3 -->
                <div class="form-group">
                  <label>Foto Pegawai Pegadaian (Kamera/Upload)</label>
                  <input type="file" accept="image/*" class="form-control" @change="handleFotoNasabah" required>
                  <small class="text-muted">Untuk web app, gunakan file input biasa atau kamera hp.</small>
                </div>
                
                <div class="form-group">
                  <label>Upload Foto Barang</label>
                  <input type="file" accept="image/*" class="form-control" @change="handleFotoBarang" required>
                </div>
                
                <div class="form-group">
                  <label>Status</label>
                  <input type="text" class="form-control" value="digadai" readonly required>
                </div>
                
                <div class="form-group">
                  <label>Akses Locker</label>
                  <div>
                    <button type="button" class="btn btn-success" @click="openAksesLocker">
                       Open / Close Locker ({{ activeLocker.kode_locker }})
                    </button>
                  </div>
                </div>
               
                <div class="form-group">
                  <label>Locker yang dipilih</label>
                  <input type="text" class="form-control" :value="activeLocker.kode_locker" readonly>
                </div>
              </div>

              <div class="mt-4" v-if="formData.rahin_id">
                <button type="submit" class="btn btn-success me-2" :disabled="loadingSubmit">
                  {{ loadingSubmit ? 'Menyimpan...' : 'Submit' }}
                </button>
                <button type="button" class="btn btn-secondary" @click="resetForm">Reset</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, nextTick } from 'vue'

import { collection, getDocs, addDoc, updateDoc, doc, query, where } from 'firebase/firestore'
import { ref as storageRef, uploadBytes, getDownloadURL } from 'firebase/storage'

definePageMeta({
  layout: 'nasabah',
  middleware: ['auth', 'nasabah']
})

const { $db, $storage } = useNuxtApp()
const { user } = useAuth()
const router = useRouter()

const availableLockers = ref([])
const selectedLockerCode = ref('')
const activeLocker = ref(null)
const searchError = ref(false)

const availableRahins = ref([])
const jenisBarangList = ref([])

const loadingSubmit = ref(false)

const currentDate = new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' })

const formData = ref({
  rahin_id: '',
  nik_rahin: '',
  no_whatsapp: '',
  email: '',
  jenis_id: '',
  deskripsi_barang: '',
  foto_nasabah: null,
  foto_barang: null
})

const loadDependencies = async () => {
  try {
    const pId = user.value?.pegadaian_id
    if (!pId) return

    // Load empty lockers
    const lSnap = await getDocs(query(
      collection($db, 'lockers'), 
      where('pegadaian_id', '==', pId),
      where('status', '==', 'belum_terisi')
    ))
    availableLockers.value = lSnap.docs.map(d => ({ id: d.id, ...d.data() }))

    // Load jenis barang
    const jSnap = await getDocs(query(collection($db, 'jenis_barang'), where('pegadaian_id', '==', pId)))
    jenisBarangList.value = jSnap.docs.map(d => ({ id: d.id, ...d.data() }))

    // Load rahin
    const rSnap = await getDocs(query(collection($db, 'rahin'), where('pegadaian_id', '==', pId)))
    const allRahin = rSnap.docs.map(d => ({ id: d.id, ...d.data() }))
    
    // Check which ones are already pawning
    const bgSnap = await getDocs(query(collection($db, 'barang_gadai'), where('pegadaian_id', '==', pId)))
    const usedRahinIds = bgSnap.docs.map(d => d.data().rahin_id)
    
    availableRahins.value = allRahin.filter(r => !usedRahinIds.includes(r.id))
  } catch (err) {
    console.error('Failed to load dependencies', err)
  }
}

onMounted(() => {
  loadDependencies()
})

const searchLocker = () => {
  const found = availableLockers.value.find(l => l.kode_locker === selectedLockerCode.value)
  if (found) {
    activeLocker.value = found
    searchError.value = false
  } else {
    activeLocker.value = null
    searchError.value = true
  }
}

const onRahinChange = () => {
  const selected = availableRahins.value.find(r => r.id === formData.value.rahin_id)
  if (selected) {
    formData.value.nik_rahin = selected.nik_rahin
    formData.value.no_whatsapp = selected.no_whatsapp
    formData.value.email = selected.email
  } else {
    formData.value.nik_rahin = ''
    formData.value.no_whatsapp = ''
    formData.value.email = ''
  }
}

const handleFotoNasabah = (e) => {
  if (e.target.files.length > 0) {
    formData.value.foto_nasabah = e.target.files[0]
  }
}

const handleFotoBarang = (e) => {
  if (e.target.files.length > 0) {
    formData.value.foto_barang = e.target.files[0]
  }
}

const openAksesLocker = () => {
  alert(`Mengirim perintah buka locker ${activeLocker.value.kode_locker} ke NodeMCU...`)
}

const resetForm = () => {
  formData.value = {
    rahin_id: '', nik_rahin: '', no_whatsapp: '', email: '',
    jenis_id: '', deskripsi_barang: '', foto_nasabah: null, foto_barang: null
  }
  activeLocker.value = null
  selectedLockerCode.value = ''
}

const uploadFile = async (file, path) => {
  if (!file) return ''
  const fRef = storageRef($storage, path)
  await uploadBytes(fRef, file)
  return await getDownloadURL(fRef)
}

const submitGadai = async () => {
  loadingSubmit.value = true
  try {
    const timestamp = Date.now()
    
    const fotoNasabahUrl = await uploadFile(
      formData.value.foto_nasabah, 
      `gadai/${user.value.pegadaian_id}/${timestamp}_nasabah_${formData.value.foto_nasabah.name}`
    )
    
    const fotoBarangUrl = await uploadFile(
      formData.value.foto_barang, 
      `gadai/${user.value.pegadaian_id}/${timestamp}_barang_${formData.value.foto_barang.name}`
    )

    // 1. Add to barang_gadai
    await addDoc(collection($db, 'barang_gadai'), {
      pegadaian_id: user.value.pegadaian_id,
      nasabah_id: user.value.id, // nasabah = pegawai here
      locker_id: activeLocker.value.id,
      rahin_id: formData.value.rahin_id,
      jenis_id: formData.value.jenis_id,
      deskripsi_barang: formData.value.deskripsi_barang,
      foto_nasabah: fotoNasabahUrl,
      foto_barang: fotoBarangUrl,
      tgl_digadai: new Date().toISOString().split('T')[0],
      status_barang: 'digadai'
    })

    // 2. Update locker status
    const lRef = doc($db, 'lockers', activeLocker.value.id)
    await updateDoc(lRef, { status: 'terisi' })

    alert('Berhasil menyimpan data gadai!')
    router.push('/nasabah/barang-digadaikan')
  } catch (error) {
    console.error('Error saving:', error)
    alert('Gagal menyimpan data gadai.')
  } finally {
    loadingSubmit.value = false
  }
}
</script>
