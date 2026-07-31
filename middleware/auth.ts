// Middleware: cek apakah user sudah login
export default defineNuxtRouteMiddleware((to) => {
  if (import.meta.client) {
    const stored = localStorage.getItem('user')
    if (!stored) {
      return navigateTo('/login')
    }
  }
})
