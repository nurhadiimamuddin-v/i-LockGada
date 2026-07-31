<template>
  <div style="padding: 50px; text-align: center; font-family: sans-serif;">
    <h2>Database Seeder</h2>
    <p v-if="loading">Menyisipkan data ke Firebase...</p>
    <p v-else-if="success" style="color: green;">Data berhasil disisipkan! Anda sekarang bisa login.</p>
    <p v-else style="color: red;">{{ errorMsg }}</p>
    
    <NuxtLink v-if="success" to="/login" style="display: inline-block; margin-top: 20px; padding: 10px 20px; background: #0aaa4e; color: white; text-decoration: none; border-radius: 5px;">
      Pergi ke Halaman Login
    </NuxtLink>
  </div>
</template>

<script setup>
import { collection, addDoc, getDocs } from 'firebase/firestore'

const { $db } = useNuxtApp()
const loading = ref(true)
const success = ref(false)
const errorMsg = ref('')

onMounted(async () => {
  try {
    const db = $db
    // Cek apakah sudah ada pegadaian
    const pSnap = await getDocs(collection(db, "pegadaian"))
    let pId = null
    if (!pSnap.empty) {
      pId = pSnap.docs[0].id
    } else {
      const pRef = await addDoc(collection(db, "pegadaian"), {
        kode_pegadaian: "PGD-001",
        lokasi_pegadaian: "Cabang Utama"
      })
      pId = pRef.id
    }

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
      await addDoc(collection(db, "users"), user)
    }
    
    success.value = true
  } catch (err) {
    console.error(err)
    errorMsg.value = "Gagal: " + err.message
  } finally {
    loading.value = false
  }
})
</script>
