import { collection, addDoc, getDocs } from 'firebase/firestore'

export default defineNuxtPlugin(async (nuxtApp) => {
  if (localStorage.getItem('seeded_1')) return // Only run once

  const db = nuxtApp.$db
  try {
    // Try to find a pegadaian
    const pSnap = await getDocs(collection(db, "pegadaian"))
    let pId = null
    if (!pSnap.empty) {
      pId = pSnap.docs[0].id
      console.log("Using pegadaian ID:", pId)
    } else {
      const pRef = await addDoc(collection(db, "pegadaian"), {
        kode_pegadaian: "PGD-001",
        lokasi_pegadaian: "Cabang Utama"
      })
      pId = pRef.id
      console.log("Created new pegadaian ID:", pId)
    }

    const users = [
      {
        nama: "Nurhadi Imamuddin (Direktur)",
        username: "nurhadiimamuddin",
        password: "180105direk",
        role: "direktur",
        pegadaian_id: pId
      },
      {
        nama: "Nurhadi Imamuddin (Manager)",
        username: "nurhadiimamuddin",
        password: "180105rah",
        role: "manager",
        pegadaian_id: pId
      },
      {
        nama: "Nurhadi Imamuddin (Nasabah)",
        username: "nurhadiimamuddin",
        nik: "nurhadiimamuddin",
        password: "180105nas",
        role: "nasabah",
        pegadaian_id: pId
      }
    ]

    for (const user of users) {
      await addDoc(collection(db, "users"), user)
      console.log(`Added user: ${user.role} - ${user.username}`)
    }
    
    localStorage.setItem('seeded_1', 'true')
    alert('User seeding complete! Check console for details.')
  } catch (err) {
    console.error(err)
  }
})
