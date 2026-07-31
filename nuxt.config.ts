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
        { rel: 'shortcut icon', href: '/images/9.png' },
        { rel: 'stylesheet', href: '/vendors/mdi/css/materialdesignicons.min.css' },
        { rel: 'stylesheet', href: '/vendors/base/vendor.bundle.base.css' },
        { rel: 'stylesheet', href: '/vendors/datatables.net-bs4/dataTables.bootstrap4.css' },
        { rel: 'stylesheet', href: 'https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css' },
        { rel: 'stylesheet', href: 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' },
        { rel: 'stylesheet', href: '/DataTables/datatables.css' },
        { rel: 'stylesheet', href: '/css/style.css' }
      ],
      script: [
        { src: 'https://code.jquery.com/jquery-3.7.1.js', body: true },
        { src: 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', body: true },
        { src: '/vendors/base/vendor.bundle.base.js', body: true },
        { src: '/vendors/chart.js/Chart.min.js', body: true },
        { src: '/vendors/datatables.net/jquery.dataTables.js', body: true },
        { src: '/vendors/datatables.net-bs4/dataTables.bootstrap4.js', body: true },
        { src: 'https://cdn.datatables.net/2.2.2/js/dataTables.js', body: true },
        { src: '/DataTables/datatables.js', body: true },
        { src: '/js/off-canvas.js', body: true },
        { src: '/js/hoverable-collapse.js', body: true },
        { src: '/js/template.js', body: true },
        { src: '/js/dashboard.js', body: true },
        { src: '/js/data-table.js', body: true }
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
