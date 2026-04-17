import axios from 'axios'

// Buat axios instance dengan base URL /api (proxy ke localhost:8000)
const api = axios.create({
  baseURL: '/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
})

// Request interceptor: otomatis inject token ke setiap request
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('auth_token')
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  },
  (error) => Promise.reject(error)
)

// Response interceptor: handle 401 (token expired/invalid)
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response && error.response.status === 401) {
      // Hapus semua data auth
      localStorage.removeItem('auth_token')
      localStorage.removeItem('auth_user')
      // Redirect ke halaman login
      window.location.href = '/login'
    }
    return Promise.reject(error)
  }
)

export default api
