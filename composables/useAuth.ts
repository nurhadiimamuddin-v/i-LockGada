// Composable untuk mengelola state autentikasi (menggantikan $_SESSION PHP)
export const useAuth = () => {
  const user = useState('auth_user', () => null as any)

  // Load user dari localStorage saat pertama kali
  if (import.meta.client && !user.value) {
    const stored = localStorage.getItem('user')
    if (stored) {
      try {
        user.value = JSON.parse(stored)
      } catch (e) {
        localStorage.removeItem('user')
      }
    }
  }

  const login = (userData: any) => {
    user.value = userData
    if (import.meta.client) {
      localStorage.setItem('user', JSON.stringify(userData))
    }
  }

  const logout = () => {
    user.value = null
    if (import.meta.client) {
      localStorage.removeItem('user')
    }
    navigateTo('/login')
  }

  const isLoggedIn = computed(() => !!user.value)
  const userRole = computed(() => user.value?.role || null)
  const userName = computed(() => user.value?.nama || '')
  const userUsername = computed(() => user.value?.username || user.value?.nik || '')

  return {
    user,
    login,
    logout,
    isLoggedIn,
    userRole,
    userName,
    userUsername
  }
}
