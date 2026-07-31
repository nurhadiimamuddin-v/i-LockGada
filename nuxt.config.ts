// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  devtools: { enabled: false },
  ssr: false, // SPA mode - semua rendering di client (seperti PHP yang render di server tapi kita ganti ke client)
  
  app: {
    head: {
      charset: 'utf-8',
      viewport: 'width=device-width, initial-scale=1, shrink-to-fit=no',
      title: 'Smart Locker Pegadaian',
      link: [
        { rel: 'shortcut icon', href: '/images/9.png' },
        { rel: 'stylesheet', href: '/vendors/mdi/css/materialdesignicons.min.css' },
        { rel: 'stylesheet', href: '/vendors/base/vendor.bundle.base.css' },
        { rel: 'stylesheet', href: 'https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css' },
        { rel: 'stylesheet', href: 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' },
        { rel: 'stylesheet', href: '/css/style.css' }
      ],
      script: [
        { src: '/vendors/base/vendor.bundle.base.js', body: true },
        { src: 'https://code.jquery.com/jquery-3.7.1.min.js', body: true },
        { src: 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', body: true },
        { src: 'https://cdn.datatables.net/2.2.2/js/dataTables.js', body: true },
        { src: '/js/off-canvas.js', body: true },
        { src: '/js/hoverable-collapse.js', body: true },
        { src: '/js/template.js', body: true }
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
