<template>
  <div style="padding: 50px; text-align: center; font-family: sans-serif;">
    <h2>Database Seeder</h2>
    <p v-if="loading">Menyisipkan data ke Firebase... <br><small>{{ statusMsg }}</small></p>
    <p v-else-if="success" style="color: green;">Data berhasil disisipkan! Anda sekarang bisa login.</p>
    <p v-else style="color: red;">{{ errorMsg }}</p>
    
    <NuxtLink v-if="success" to="/login" style="display: inline-block; margin-top: 20px; padding: 10px 20px; background: #0aaa4e; color: white; text-decoration: none; border-radius: 5px;">
      Pergi ke Halaman Login
    </NuxtLink>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, nextTick } from 'vue'

const config = useRuntimeConfig()

const loading = ref(true)
const success = ref(false)
const errorMsg = ref('')
const statusMsg = ref('')

const PROJECT_ID = config.public.firebaseProjectId
const API_KEY = config.public.firebaseApiKey
const BASE_URL = `https://firestore.googleapis.com/v1/projects/${PROJECT_ID}/databases/(default)/documents`

// Helper: Tambah dokumen via REST API
async function restAddDoc(collectionName, data) {
  const url = `${BASE_URL}/${collectionName}?key=${API_KEY}`
  const fields = {}
  for (const [key, value] of Object.entries(data)) {
    if (value === null || value === undefined) {
      fields[key] = { nullValue: null }
    } else if (typeof value === 'string') {
      fields[key] = { stringValue: value }
    } else if (typeof value === 'number') {
      fields[key] = { integerValue: String(value) }
    } else if (typeof value === 'boolean') {
      fields[key] = { booleanValue: value }
    }
  }

  const res = await fetch(url, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ fields })
  })

  if (!res.ok) {
    const err = await res.json()
    throw new Error(`REST API Error: ${err.error?.message || res.statusText}`)
  }

  const doc = await res.json()
  // Extract document ID from name like "projects/.../documents/collectionName/docId"
  const docId = doc.name.split('/').pop()
  return docId
}

// Helper: Baca dokumen via REST API
async function restGetDocs(collectionName) {
  const url = `${BASE_URL}/${collectionName}?key=${API_KEY}`
  const res = await fetch(url)

  if (!res.ok) {
    const err = await res.json()
    throw new Error(`REST API Error: ${err.error?.message || res.statusText}`)
  }

  const data = await res.json()
  return data.documents || []
}

onMounted(async () => {
  try {
    console.log("--- Memulai proses seeding (REST API) ---")
    
    // 1. Cek pegadaian
    statusMsg.value = 'Mengecek data pegadaian...'
    console.log("1. Mengecek collection pegadaian...")
    const existingDocs = await restGetDocs('pegadaian')
    console.log("2. Hasil:", existingDocs.length, "dokumen ditemukan")

    let pId = null
    if (existingDocs.length > 0) {
      pId = existingDocs[0].name.split('/').pop()
      console.log("3. Pegadaian sudah ada, id:", pId)
    } else {
      statusMsg.value = 'Membuat data pegadaian...'
      console.log("3. Membuat pegadaian baru...")
      pId = await restAddDoc('pegadaian', {
        kode_pegadaian: "PGD-001",
        lokasi_pegadaian: "Cabang Utama"
      })
      console.log("4. Pegadaian berhasil dibuat:", pId)
    }

    // 2. Insert users
    const users = [
      {
        nama: "Nurhadi Imamuddin (Direktur)",
        username: "direktur",
        password: "180105direk",
        role: "direktur",
        pegadaian_id: pId
      },
      {
        nama: "Nurhadi Imamuddin (Manager)",
        username: "manager",
        password: "180105rah",
        role: "manager",
        pegadaian_id: pId
      },
      {
        nama: "Nurhadi Imamuddin (Nasabah)",
        username: "nasabah",
        nik: "nurhadiimamuddin",
        password: "180105nas",
        role: "nasabah",
        pegadaian_id: pId
      }
    ]

    for (const user of users) {
      statusMsg.value = `Menambahkan user ${user.username}...`
      console.log("-> Insert user:", user.username)
      const id = await restAddDoc('users', user)
      console.log("-> Berhasil:", user.username, "id:", id)
    }
    
    console.log("=== Semua selesai! ===")
    success.value = true
  } catch (err) {
    console.error("ERROR:", err)
    errorMsg.value = "Gagal: " + err.message
  } finally {
    loading.value = false
  }
})
</script>
