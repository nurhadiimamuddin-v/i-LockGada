// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  runtimeConfig: {
    public: {
      firebaseApiKey: process.env.NUXT_PUBLIC_FIREBASE_API_KEY,
      firebaseAuthDomain: process.env.NUXT_PUBLIC_FIREBASE_AUTH_DOMAIN,
      firebaseProjectId: process.env.NUXT_PUBLIC_FIREBASE_PROJECT_ID,
      firebaseStorageBucket: process.env.NUXT_PUBLIC_FIREBASE_STORAGE_BUCKET,
      firebaseMessagingSenderId: process.env.NUXT_PUBLIC_FIREBASE_MESSAGING_SENDER_ID,
      firebaseAppId: process.env.NUXT_PUBLIC_FIREBASE_APP_ID,
      firebaseMeasurementId: process.env.NUXT_PUBLIC_FIREBASE_MEASUREMENT_ID
    }
  },
  modules: [
    '@nuxtjs/tailwindcss'
  ],
  devtools: { enabled: false },
  ssr: false, // SPA mode - semua rendering di client (seperti PHP yang render di server tapi kita ganti ke client)
  
  app: {
    head: {
      charset: 'utf-8',
      viewport: 'width=device-width, initial-scale=1, shrink-to-fit=no',
      title: 'i-LockGada',
      link: [
        { rel: 'shortcut icon', href: '/images/9.png' }
      ]
    }
  },

  // Ignore PHP files during build
  ignore: [
    '**/*.php',
    'php_backup/**',
    'PHP-Serial-develop/**',
    'vendor/**',
    'scss/**',
    'documentation/**',
    'gulpfile.js',
    'remove_bg.py',
    'router.php',
    'composer.json',
    'composer.lock'
  ],

  compatibilityDate: '2024-07-31'
})
