<template>
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between flex-wrap">
          <div class="d-flex align-items-end flex-wrap">
            <div class="mr-md-3 mr-xl-5">
              <h2>Data Barang yang akan diterima</h2>
              <p class="mb-md-0">Silahkan Untuk mencari <strong> NIK NASABAH </strong> terlebih dahulu</p>
              <p class="mb-md-0" style="color: red;">
                <strong>Note : </strong> Nomor Induk Kependudukan Rahin dijadikan sebagai Kode barang yang di gadaikan
              </p>
            </div>
            <div class="d-flex">
              <img src="/images/ty.png" alt="logo" style="height: 63px; margin-left: 360px;" class="d-none d-md-block">
            </div>
          </div>
        </div>   
      </div>
    </div>
        
    <div class="row">
      <div class="col-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">DATA KODE BARANG YANG DIGADAIKAN</h4>
            
            <form class="forms-sample d-flex align-items-center" @submit.prevent="searchGadai">
              <div class="form-group flex-grow-1">
                <label>Pilih NIK Nasabah (Barang Masih Digadai):</label>
                <select class="form-control" v-model="selectedNik" required>
                  <option value="" disabled>Pilih NIK</option>
                  <option v-for="item in availableGadai" :key="item.id" :value="item.nik_rahin">
                    {{ item.nik_rahin }} - {{ item.nama_rahin }}
                  </option>
                </select>  
              </div>
              <div class="form-group ms-3" style="margin-left: 1rem;">
                <button type="submit" class="btn mt-4" style="color: white; background-color: #20a1ad;">Search</button>
              </div>
            </form>

            <div v-if="searchError" class="alert alert-danger mt-3">
              Data dengan NIK tersebut tidak ditemukan atau tidak dalam status "digadai".
            </div>
            
            <div v-if="activeGadai" class="alert alert-success mt-3">
              Ditemukan data dengan NIK: <strong>{{ activeGadai.nik_rahin }}</strong>
            </div>
          </div>
        </div>
      </div>
    </div>
            
    <div class="row" v-if="activeGadai">
      <div class="col-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">FORM BARANG YANG AKAN DIAMBIL</h4>
            <p class="card-description">Silahkan isi form berikut untuk mengambil barang</p>
            
            <form class="forms-sample" @submit.prevent="submitAmbil">
              <div class="form-group">
                <label>Nama Nasabah (Pegawai)</label>
                <input type="text" class="form-control" :value="user?.nama" readonly required>
              </div>
              
              <div class="form-group">
                <label>Kode Pegadaian</label>
                <input type="text" class="form-control" :value="user?.pegadaian_id" readonly required>
              </div>
              
              <div class="form-group">
                <label>Tanggal diambil</label>
                <input type="text" class="form-control" :value="currentDate" readonly>
              </div>

              <hr>

              <div class="form-group">
                <label>Verifikasi Foto Nasabah (Kamera/Upload)</label>
                <input type="file" accept="image/*" class="form-control" @change="handleFotoNasabah" required>
              </div>
              
              <div class="form-group">
                <label>Verifikasi Foto Barang</label>
                <input type="file" accept="image/*" class="form-control" @change="handleFotoBarang" required>
              </div>
              
              <div class="form-group">
                <label>Status</label>
                <input type="text" class="form-control" value="diambil" readonly required>
              </div>
              
              <div class="form-group">
                <label>Akses Locker</label>
                <div>
                  <button type="button" class="btn btn-success" @click="openAksesLocker">
                     Open / Close Locker ({{ activeGadai.kode_locker }})
                  </button>
                </div>
              </div>
             
              <div class="form-group">
                <label>Locker yang dipilih</label>
                <input type="text" class="form-control" :value="activeGadai.kode_locker" readonly>
              </div>

              <div class="mt-4">
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

const availableGadai = ref([])
const selectedNik = ref('')
const activeGadai = ref(null)
const searchError = ref(false)

const loadingSubmit = ref(false)
const currentDate = new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' })

const formData = ref({
  finishing_foto_nasabah: null,
  finishing_foto_barang: null
})

const loadDependencies = async () => {
  try {
    const pId = user.value?.pegadaian_id
    if (!pId) return

    // Load active gadai items for this pegadaian
    const bgSnap = await getDocs(query(
      collection($db, 'barang_gadai'), 
      where('pegadaian_id', '==', pId),
      where('status_barang', '==', 'digadai')
    ))
    
    // We need rahin details (nik_rahin) and locker codes
    const rahinSnap = await getDocs(query(collection($db, 'rahin'), where('pegadaian_id', '==', pId)))
    const rahinMap = {}
    rahinSnap.forEach(d => { rahinMap[d.id] = d.data() })

    const lockerSnap = await getDocs(query(collection($db, 'lockers'), where('pegadaian_id', '==', pId)))
    const lockerMap = {}
    lockerSnap.forEach(d => { lockerMap[d.id] = d.data() })

    const temp = []
    bgSnap.forEach(d => {
      const data = d.data()
      const rData = rahinMap[data.rahin_id]
      const lData = lockerMap[data.locker_id]
      if (rData && lData) {
        temp.push({
          id: d.id, // barang_gadai ID
          rahin_id: data.rahin_id,
          jenis_id: data.jenis_id,
          locker_id: data.locker_id,
          nik_rahin: rData.nik_rahin,
          nama_rahin: rData.nama_rahin,
          kode_locker: lData.kode_locker
        })
      }
    })
    
    // Filter distinct NIKs for the dropdown
    const unique = []
    const seen = new Set()
    for (const item of temp) {
      if (!seen.has(item.nik_rahin)) {
        seen.add(item.nik_rahin)
        unique.push(item)
      }
    }
    
    availableGadai.value = unique
  } catch (err) {
    console.error('Failed to load dependencies', err)
  }
}

onMounted(() => {
  loadDependencies()
})

const searchGadai = () => {
  const found = availableGadai.value.find(g => g.nik_rahin === selectedNik.value)
  if (found) {
    activeGadai.value = found
    searchError.value = false
  } else {
    activeGadai.value = null
    searchError.value = true
  }
}

const handleFotoNasabah = (e) => {
  if (e.target.files.length > 0) {
    formData.value.finishing_foto_nasabah = e.target.files[0]
  }
}

const handleFotoBarang = (e) => {
  if (e.target.files.length > 0) {
    formData.value.finishing_foto_barang = e.target.files[0]
  }
}

const openAksesLocker = () => {
  alert(`Mengirim perintah buka locker ${activeGadai.value.kode_locker} ke NodeMCU...`)
}

const resetForm = () => {
  formData.value = { finishing_foto_nasabah: null, finishing_foto_barang: null }
  activeGadai.value = null
  selectedNik.value = ''
}

const uploadFile = async (file, path) => {
  if (!file) return ''
  const fRef = storageRef($storage, path)
  await uploadBytes(fRef, file)
  return await getDownloadURL(fRef)
}

const submitAmbil = async () => {
  loadingSubmit.value = true
  try {
    const pId = user.value.pegadaian_id
    const timestamp = Date.now()
    
    const fotoNasabahUrl = await uploadFile(
      formData.value.finishing_foto_nasabah, 
      `ambil/${pId}/${timestamp}_nasabah_${formData.value.finishing_foto_nasabah?.name || 'photo'}`
    )
    
    const fotoBarangUrl = await uploadFile(
      formData.value.finishing_foto_barang, 
      `ambil/${pId}/${timestamp}_barang_${formData.value.finishing_foto_barang?.name || 'photo'}`
    )

    // 1. Add to barang_diambil
    await addDoc(collection($db, 'barang_diambil'), {
      pegadaian_id: pId,
      nasabah_id: user.value.id, // pegawai
      locker_id: activeGadai.value.locker_id,
      jenis_id: activeGadai.value.jenis_id,
      rahin_id: activeGadai.value.rahin_id,
      barang_id: activeGadai.value.id, // original gadai id
      tgl_diterima: new Date().toISOString().split('T')[0],
      finishing_foto_nasabah: fotoNasabahUrl,
      finishing_foto_barang: fotoBarangUrl
    })

    // 2. Update barang_gadai status
    const bgRef = doc($db, 'barang_gadai', activeGadai.value.id)
    await updateDoc(bgRef, { status_barang: 'diambil' })

    // 3. Update locker status
    const lRef = doc($db, 'lockers', activeGadai.value.locker_id)
    await updateDoc(lRef, { status: 'belum_terisi' })

    alert('Berhasil menyimpan data pengambilan barang!')
    router.push('/nasabah/barang-diambil')
  } catch (error) {
    console.error('Error saving:', error)
    alert('Gagal menyimpan data pengambilan barang.')
  } finally {
    loadingSubmit.value = false
  }
}
</script>
