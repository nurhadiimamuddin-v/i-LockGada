import { initializeApp } from "firebase/app";
import { getFirestore } from "firebase/firestore";
import { getStorage } from "firebase/storage";
import { initializeAppCheck, ReCaptchaV3Provider } from "firebase/app-check";

let app = null;
let db = null;
let storage = null;

export const initFirebase = (config, appCheckSiteKey) => {
  if (!app) {
    app = initializeApp(config);
    db = getFirestore(app);
    storage = getStorage(app);
    
    // Inisialisasi App Check (Hanya aktif di client)
    if (typeof window !== 'undefined' && appCheckSiteKey) {
      initializeAppCheck(app, {
        provider: new ReCaptchaV3Provider(appCheckSiteKey),
        isTokenAutoRefreshEnabled: true
      });
    }

  }
  return { app, db, storage };
};

export const getDb = () => db;
