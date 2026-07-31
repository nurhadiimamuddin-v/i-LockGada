import { initializeApp } from 'firebase/app';
import { getFirestore, collection, addDoc, getDocs } from 'firebase/firestore';

const firebaseConfig = {
  apiKey: "AIzaSyBYyoKn58N8Ns0aAwFJ104SPq1PPgCr53I",
  authDomain: "i-lockgada.firebaseapp.com",
  projectId: "i-lockgada",
  storageBucket: "i-lockgada.firebasestorage.app",
  messagingSenderId: "994810445842",
  appId: "1:994810445842:web:f893e930ee492c262158cb",
  measurementId: "G-WYPEML2CGW"
};

const app = initializeApp(firebaseConfig);
const db = getFirestore(app);

async function test() {
  try {
    console.log("Getting docs...");
    const snap = await getDocs(collection(db, "pegadaian"));
    console.log("Got docs. Count:", snap.size);

    console.log("Adding doc...");
    const ref = await addDoc(collection(db, "test_collection"), { time: Date.now() });
    console.log("Added doc successfully:", ref.id);
  } catch (err) {
    console.error("Error:", err);
  }
  process.exit(0);
}

test();
