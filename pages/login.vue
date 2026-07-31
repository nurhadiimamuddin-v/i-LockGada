<template>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                <img src="/images/44.png" alt="logo" style="width: 250px; height: auto;">
                <h4 class="mt-1">Aplikasi i-LockGada Pegadaian</h4>
                
              <h6 class="font-weight-light">Sign in to continue.</h6>

              <div v-if="errorMsg" class="alert alert-danger" role="alert">
                {{ errorMsg }}
              </div>

              <form class="pt-3" @submit.prevent="handleLogin">
                <div class="form-group">
                  <input type="text" class="form-control form-control-lg" v-model="username" placeholder="Username / NIK" required>
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" v-model="password" placeholder="Password" required>
                </div>
                <div class="mt-3">
                  <button class="btn btn-block btn-success btn-lg font-weight-medium auth-form-btn" type="submit" :disabled="loading">
                    {{ loading ? 'SIGNING IN...' : 'SIGN IN' }}
                  </button>
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { collection, query, where, getDocs } from 'firebase/firestore'

definePageMeta({
  layout: 'default'
})

const { $db } = useNuxtApp()
const { login } = useAuth()

const username = ref('')
const password = ref('')
const errorMsg = ref('')
const loading = ref(false)

const handleLogin = async () => {
  errorMsg.value = ''
  loading.value = true

  try {
    const usersRef = collection($db, 'users')
    let matchedUser = null

    // Pertama, cari berdasarkan username
    const qUsername = query(usersRef, where('username', '==', username.value))
    const snapUsername = await getDocs(qUsername)
    
    if (!snapUsername.empty) {
      // Cari yang passwordnya cocok
      const doc = snapUsername.docs.find(d => d.data().password === password.value)
      if (doc) {
        matchedUser = { id: doc.id, ...doc.data() }
      }
    }

    // Jika tidak ketemu berdasarkan username, cari berdasarkan NIK (untuk nasabah)
    if (!matchedUser) {
      const qNik = query(usersRef, where('nik', '==', username.value))
      const snapNik = await getDocs(qNik)
      
      if (!snapNik.empty) {
        const doc = snapNik.docs.find(d => d.data().password === password.value)
        if (doc) {
          matchedUser = { id: doc.id, ...doc.data() }
        }
      }
    }

    if (matchedUser) {
      login(matchedUser)
      if (matchedUser.role === 'direktur') navigateTo('/direktur')
      else if (matchedUser.role === 'manager') navigateTo('/manager')
      else if (matchedUser.role === 'nasabah') navigateTo('/nasabah')
    } else {
      errorMsg.value = 'Login gagal! Username/NIK atau password salah.'
    }

  } catch (error) {
    console.error('Login error:', error)
    errorMsg.value = 'Terjadi kesalahan koneksi.'
  } finally {
    loading.value = false
  }
}

useHead({
  link: [
    { rel: 'stylesheet', href: '/vendors/mdi/css/materialdesignicons.min.css' },
    { rel: 'stylesheet', href: '/vendors/base/vendor.bundle.base.css' },
    { rel: 'stylesheet', href: '/css/style.css' }
  ],
  script: [
    { src: '/vendors/base/vendor.bundle.base.js', body: true }
  ]
})
</script>

