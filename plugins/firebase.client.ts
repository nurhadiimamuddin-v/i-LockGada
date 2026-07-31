import { initializeApp } from 'firebase/app'
import { getFirestore } from 'firebase/firestore'
import { getStorage } from 'firebase/storage'

export default defineNuxtPlugin(() => {
  const firebaseConfig = {
    apiKey: "AIzaSyBYyoKn58N8Ns0aAwFJ104SPq1PPgCr53I",
    authDomain: "i-lockgada.firebaseapp.com",
    projectId: "i-lockgada",
    storageBucket: "i-lockgada.firebasestorage.app",
    messagingSenderId: "994810445842",
    appId: "1:994810445842:web:f893e930ee492c262158cb",
    measurementId: "G-WYPEML2CGW"
  }

  const app = initializeApp(firebaseConfig)
  const db = getFirestore(app)
  const storage = getStorage(app)

  return {
    provide: {
      firebaseApp: app,
      db,
      storage
    }
  }
})
