import { initializeApp } from "firebase/app";
import { getFirestore } from "firebase/firestore";
import { getStorage } from "firebase/storage";

let app = null;
let db = null;
let storage = null;

export const initFirebase = (config) => {
  if (!app) {
    app = initializeApp(config);
    db = getFirestore(app);
    storage = getStorage(app);
  }
  return { app, db, storage };
};

export const getDb = () => db;
