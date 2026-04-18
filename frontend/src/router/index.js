import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('@/views/HomeView.vue')
    },
    {
      path: '/absensi',
      name: 'absensi',
      component: () => import('@/views/AbsensiView.vue')
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('@/views/LoginView.vue'),
      meta: { guestOnly: true }
    },
    {
      path: '/register',
      name: 'register',
      component: () => import('@/views/RegisterView.vue'),
      meta: { guestOnly: true }
    },
    {
      path: '/admin',
      component: () => import('@/layouts/AdminLayout.vue'),
      meta: { requiresAuth: true },
      children: [
        {
          path: '',
          redirect: '/admin/dashboard'
        },
        {
          path: 'dashboard',
          name: 'admin-dashboard',
          component: () => import('@/views/admin/DashboardView.vue')
        },
        {
          path: 'absensi-siswa',
          name: 'admin-absensi',
          component: () => import('@/views/admin/AbsensiSiswaView.vue')
        },
        {
          path: 'data-siswa',
          name: 'admin-data-siswa',
          component: () => import('@/views/admin/DataSiswaView.vue')
        },
        {
          path: 'tambah-siswa',
          name: 'admin-tambah-siswa',
          component: () => import('@/views/admin/TambahSiswaView.vue')
        },
        {
          path: 'import-siswa',
          name: 'admin-import-siswa',
          component: () => import('@/views/admin/ImportSiswaView.vue')
        },
        {
          path: 'kelas',
          name: 'admin-kelas',
          component: () => import('@/views/admin/KelasView.vue')
        }
      ]
    }
  ]
})

// Navigation Guard
router.beforeEach((to, from) => {
  const token = localStorage.getItem('auth_token')
  const isLoggedIn = !!token

  if (to.meta.requiresAuth && !isLoggedIn) {
    // Halaman admin butuh login
    return { name: 'login' }
  } else if (to.meta.guestOnly && isLoggedIn) {
    // Kalau sudah login, jangan ke login/register lagi
    return { name: 'admin-dashboard' }
  }
  return true
})

export default router
