// Middleware: cek role manager (menggantikan pengecekan di atas.php)
export default defineNuxtRouteMiddleware(() => {
  if (import.meta.client) {
    const stored = localStorage.getItem('user')
    if (!stored) return navigateTo('/login')
    try {
      const user = JSON.parse(stored)
      if (user.role !== 'manager') return navigateTo('/login')
    } catch { return navigateTo('/login') }
  }
})
