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
