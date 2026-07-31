// Middleware: cek role nasabah (menggantikan pengecekan di header.php)
export default defineNuxtRouteMiddleware(() => {
  if (import.meta.client) {
    const stored = localStorage.getItem('user')
    if (!stored) return navigateTo('/login')
    try {
      const user = JSON.parse(stored)
      if (user.role !== 'nasabah') return navigateTo('/login')
    } catch { return navigateTo('/login') }
  }
})
