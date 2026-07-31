import { initializeApp } from 'firebase/app'
import { getFirestore, collection, addDoc, getDocs } from 'firebase/firestore'

const firebaseConfig = {
  apiKey: "AIzaSyBYyoKn58N8Ns0aAwFJ104SPq1PPgCr53I",
  authDomain: "i-lockgada.firebaseapp.com",
  projectId: "i-lockgada",
  storageBucket: "i-lockgada.firebasestorage.app",
  messagingSenderId: "994810445842",
  appId: "1:994810445842:web:f893e930ee492c262158cb"
}

const app = initializeApp(firebaseConfig)
const db = getFirestore(app)

const seed = async () => {
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
      username: "nurhadiimamuddin", // For reference
      nik: "nurhadiimamuddin",     // Login for nasabah uses NIK
      password: "180105nas",
      role: "nasabah",
      pegadaian_id: pId
    }
  ]

  for (const user of users) {
    await addDoc(collection(db, "users"), user)
    console.log(`Added user: ${user.role} - ${user.username}`)
  }
  process.exit(0)
}

seed()
