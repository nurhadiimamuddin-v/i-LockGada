// Middleware: cek role direktur (menggantikan pengecekan di head.php)
export default defineNuxtRouteMiddleware(() => {
  if (import.meta.client) {
    const stored = localStorage.getItem('user')
    if (!stored) return navigateTo('/login')
    try {
      const user = JSON.parse(stored)
      if (user.role !== 'direktur') return navigateTo('/login')
    } catch { return navigateTo('/login') }
  }
})
