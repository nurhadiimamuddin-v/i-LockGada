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
  console.log("=== Memulai proses seeding ===")
  try {
    const db = $db
    if (!db) throw new Error("Firestore db object is undefined!")
    console.log("1. Firestore instance didapatkan")
    
    // Cek apakah sudah ada pegadaian
    console.log("2. Mencoba membaca collection 'pegadaian'...")
    const pSnap = await getDocs(collection(db, "pegadaian"))
    console.log("3. Berhasil membaca collection 'pegadaian'")
    
    let pId = null
    if (!pSnap.empty) {
      pId = pSnap.docs[0].id
      console.log("4. Pegadaian sudah ada, id:", pId)
    } else {
      console.log("4. Membuat pegadaian baru...")
      try {
        const pRef = await addDoc(collection(db, "pegadaian"), {
          kode_pegadaian: "PGD-001",
          lokasi_pegadaian: "Cabang Utama"
        })
        pId = pRef.id
        console.log("5. Berhasil membuat pegadaian:", pId)
      } catch (e) {
        console.error("Gagal membuat pegadaian:", e)
        throw e
      }
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

    console.log("6. Memulai insert users...")
    for (const user of users) {
      console.log("-> Mencoba insert user:", user.username)
      try {
        await addDoc(collection(db, "users"), user)
        console.log("-> Berhasil insert:", user.username)
      } catch (e) {
        console.error("-> Gagal insert:", user.username, e)
        throw e
      }
    }
    
    console.log("7. Semua selesai!")
    success.value = true
  } catch (err) {
    console.error(err)
    errorMsg.value = "Gagal: " + err.message
  } finally {
    loading.value = false
  }
})
</script>
