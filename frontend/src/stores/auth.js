import { ref, computed } from 'vue'

// State reaktif
const token = ref(localStorage.getItem('auth_token') || null)
const user = ref(JSON.parse(localStorage.getItem('auth_user') || 'null'))

// Computed
const isLoggedIn = computed(() => !!token.value)

// Simpan token & user setelah login berhasil
const setAuth = (newToken, newUser) => {
  token.value = newToken
  user.value = newUser
  localStorage.setItem('auth_token', newToken)
  localStorage.setItem('auth_user', JSON.stringify(newUser))
}

// Hapus token & user saat logout
const clearAuth = () => {
  token.value = null
  user.value = null
  localStorage.removeItem('auth_token')
  localStorage.removeItem('auth_user')
}

const getToken = () => token.value
const getUser = () => user.value

// Export composable
export const useAuth = () => ({
  token,
  user,
  isLoggedIn,
  setAuth,
  clearAuth,
  getToken,
  getUser,
})
