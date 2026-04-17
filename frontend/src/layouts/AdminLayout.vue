<script setup>
import { useRoute, useRouter } from 'vue-router'
import { computed } from 'vue'
import { useAuth } from '@/stores/auth'
import api from '@/services/api'

const route = useRoute()
const router = useRouter()
const { user, clearAuth } = useAuth()

const username = computed(() => user.value?.username || 'Petugas')

const isActive = (path) => route.path === path
const isActiveGroup = (...paths) => paths.some(p => route.path === p)

const logout = async () => {
  try {
    await api.post('/logout')
  } catch (e) {
    // tetap lanjut logout meskipun request gagal
  } finally {
    clearAuth()
    router.push('/login')
  }
}
</script>

<template>
  <div class="bg-surface font-body text-on-surface flex min-h-screen">
    <!-- SideNavBar Component -->
    <aside class="h-screen w-64 fixed left-0 top-0 bg-surface-container flex flex-col gap-2 p-4 border-r border-transparent z-40">
      <div class="px-4 py-6 mb-4">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center text-on-primary">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">mosque</span>
          </div>
          <div>
            <h1 class="text-lg font-bold text-primary font-headline leading-tight">Admin Panel</h1>
            <p class="text-[10px] uppercase tracking-wider text-on-surface-variant/60 font-semibold font-headline">THP Management</p>
          </div>
        </div>
      </div>

      <nav class="flex-1 space-y-1">
        <router-link to="/admin/dashboard" custom v-slot="{ navigate }">
          <a @click="navigate" :class="isActive('/admin/dashboard') ? 'bg-white text-primary shadow-sm font-bold' : 'text-slate-600 hover:bg-slate-200/50 hover:translate-x-1 font-medium'" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-transform active:opacity-80 cursor-pointer">
            <span class="material-symbols-outlined" :style="isActive('/admin/dashboard') ? 'font-variation-settings: \'FILL\' 1;' : ''">dashboard</span>
            <span class="font-['Inter'] text-sm">Dashboard</span>
          </a>
        </router-link>

        <router-link to="/admin/absensi-siswa" custom v-slot="{ navigate }">
          <a @click="navigate" :class="isActive('/admin/absensi-siswa') ? 'bg-white text-primary shadow-sm font-bold' : 'text-slate-600 hover:bg-slate-200/50 hover:translate-x-1 font-medium'" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-transform active:opacity-80 cursor-pointer">
            <span class="material-symbols-outlined" :style="isActive('/admin/absensi-siswa') ? 'font-variation-settings: \'FILL\' 1;' : ''">how_to_reg</span>
            <span class="font-['Inter'] text-sm">Absensi Siswa</span>
          </a>
        </router-link>

        <router-link to="/admin/data-siswa" custom v-slot="{ navigate }">
          <a @click="navigate" :class="isActiveGroup('/admin/data-siswa', '/admin/tambah-siswa') ? 'bg-white text-primary shadow-sm font-bold' : 'text-slate-600 hover:bg-slate-200/50 hover:translate-x-1 font-medium'" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-transform active:opacity-80 cursor-pointer">
            <span class="material-symbols-outlined" :style="isActiveGroup('/admin/data-siswa', '/admin/tambah-siswa') ? 'font-variation-settings: \'FILL\' 1;' : ''">group</span>
            <span class="font-['Inter'] text-sm">Data Siswa</span>
          </a>
        </router-link>

        <!-- Tombol Import Siswa (BARU) -->
        <router-link to="/admin/import-siswa" custom v-slot="{ navigate }">
          <a @click="navigate" :class="isActive('/admin/import-siswa') ? 'bg-white text-primary shadow-sm font-bold' : 'text-slate-600 hover:bg-slate-200/50 hover:translate-x-1 font-medium'" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-transform active:opacity-80 cursor-pointer">
            <span class="material-symbols-outlined" :style="isActive('/admin/import-siswa') ? 'font-variation-settings: \'FILL\' 1;' : ''">upload_file</span>
            <span class="font-['Inter'] text-sm">Import Siswa</span>
          </a>
        </router-link>

        <a @click="logout" class="flex items-center gap-3 px-4 py-3 text-slate-600 hover:bg-slate-200/50 rounded-lg transition-transform hover:translate-x-1 active:opacity-80 mt-4 cursor-pointer font-medium">
          <span class="material-symbols-outlined text-error">logout</span>
          <span class="font-['Inter'] text-sm text-error">Logout</span>
        </a>
      </nav>

      <!-- User Card -->
      <div class="mt-auto pt-6 border-t border-outline-variant/20 flex flex-col gap-3 px-2">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-lg">
            {{ username.charAt(0).toUpperCase() }}
          </div>
          <div class="overflow-hidden">
            <p class="text-sm font-bold text-on-surface truncate">{{ username }}</p>
            <p class="text-xs text-on-surface-variant truncate">Petugas Admin</p>
          </div>
        </div>
      </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 ml-64 overflow-y-auto bg-surface relative min-h-screen pb-16">
      <router-view></router-view>

      <!-- Footer -->
      <footer class="absolute bottom-0 right-0 w-full py-4 px-12 bg-surface/80 backdrop-blur-sm flex justify-between items-center text-on-surface-variant border-t border-outline-variant/10 pointer-events-none">
        <p class="text-[10px] font-medium tracking-tight">© 2024 e-Solat THP - The Serene Architect</p>
        <div class="flex gap-6 pointer-events-auto">
          <a class="text-[10px] font-medium hover:text-secondary transition-colors" href="#">Kebijakan Privasi</a>
          <a class="text-[10px] font-medium hover:text-secondary transition-colors" href="#">Syarat &amp; Ketentuan</a>
        </div>
      </footer>
    </main>
  </div>
</template>
