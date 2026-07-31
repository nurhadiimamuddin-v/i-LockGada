import { initFirebase } from '~/utils/firebase'
import { getAnalytics } from 'firebase/analytics'
import { markRaw } from 'vue'

export default defineNuxtPlugin(() => {
  const config = useRuntimeConfig()
  
  const firebaseConfig = {
    apiKey: config.public.firebaseApiKey,
    authDomain: config.public.firebaseAuthDomain,
    projectId: config.public.firebaseProjectId,
    storageBucket: config.public.firebaseStorageBucket,
    messagingSenderId: config.public.firebaseMessagingSenderId,
    appId: config.public.firebaseAppId,
    measurementId: config.public.firebaseMeasurementId
  }

  const { app, db, storage } = initFirebase(firebaseConfig)

  let analytics = null
  if (typeof window !== 'undefined') {
    analytics = getAnalytics(app)
  }

  return {
    provide: {
      firebaseApp: markRaw(app),
      db: markRaw(db),
      storage: markRaw(storage),
      analytics: analytics ? markRaw(analytics) : null
    }
  }
})
