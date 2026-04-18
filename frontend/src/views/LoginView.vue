<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/services/api'
import { useAuth } from '@/stores/auth'

const router = useRouter()
const { setAuth } = useAuth()

const username = ref('')
const password = ref('')
const showPassword = ref(false)
const isLoading = ref(false)
const errorMsg = ref('')

const handleLogin = async () => {
  if (!username.value || !password.value) {
    errorMsg.value = 'Username dan password wajib diisi.'
    return
  }
  isLoading.value = true
  errorMsg.value = ''
  try {
    const res = await api.post('/login', {
      username: username.value,
      password: password.value,
    })
    const data = res.data.data
    setAuth(data.token, { id_petugas: data.id_petugas, username: data.username })
    router.push('/admin/dashboard')
  } catch (err) {
    if (err.response && err.response.status === 401) {
      errorMsg.value = 'Username atau password salah.'
    } else {
      errorMsg.value = 'Terjadi kesalahan. Coba lagi.'
    }
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
<div class="bg-surface font-body text-on-surface min-h-screen flex flex-col relative overflow-hidden">
  <!-- Responsive Background Images -->
  <div class="absolute inset-0 z-0 pointer-events-none">
    <img src="/images/Desktop.jpeg" alt="Background Desktop" class="w-full h-full object-cover hidden md:block opacity-20 mix-blend-multiply" />
    <img src="/images/Mobile.jpeg" alt="Background Mobile" class="w-full h-full object-cover block md:hidden opacity-20 mix-blend-multiply" />
    <div class="absolute inset-0 bg-surface/80 backdrop-blur-[2px]"></div>
  </div>
  <!-- Top Navigation Bar -->
  <nav class="fixed top-0 w-full z-50 bg-[#f7f9fc]/80 dark:bg-slate-900/80 backdrop-blur-md shadow-sm dark:shadow-none">
    <div class="flex justify-between items-center px-8 py-4 max-w-full">
      <div class="text-xl font-bold text-[#0050cb] dark:text-blue-400 font-headline cursor-pointer" @click="router.push('/')">
        e-Solat THP
      </div>
      <div class="hidden md:flex gap-8 items-center">
        <a class="text-slate-600 dark:text-slate-400 font-headline font-semibold hover:text-[#0050cb] transition-colors" href="#" @click.prevent="router.push('/')">Beranda</a>
        <a class="text-slate-600 dark:text-slate-400 font-headline font-semibold hover:text-[#0050cb] transition-colors" href="#">Tentang</a>
        <a class="text-slate-600 dark:text-slate-400 font-headline font-semibold hover:text-[#0050cb] transition-colors" href="#">Panduan</a>
      </div>
      <div class="flex gap-4 items-center">
        <button @click="router.push('/register')" class="text-slate-600 dark:text-slate-400 font-headline font-semibold hover:text-[#0050cb] transition-colors">Daftar</button>
        <button class="bg-primary text-on-primary px-6 py-2 rounded-full font-headline font-semibold scale-95 duration-200 active:opacity-80">Masuk</button>
      </div>
    </div>
  </nav>

  <!-- Main Content Canvas -->
  <main class="flex-grow flex items-center justify-center pt-20 px-4 md:px-12 relative z-10">
    <div class="max-w-[1440px] w-full grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
      <!-- Left Side: Welcome Intro -->
      <div class="space-y-8 lg:pr-16">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/10 text-primary font-label text-sm font-semibold">
          <span class="material-symbols-outlined text-sm">verified_user</span>
          Portal Administrasi Terpadu
        </div>
        <h1 class="text-5xl lg:text-7xl font-headline font-bold text-on-surface tracking-tight leading-[1.1]">
          Selamat Datang <br/>
          <span class="text-primary">Petugas THP.</span>
        </h1>
        <p class="text-lg text-on-surface-variant leading-relaxed max-w-xl">
          Kelola kehadiran ibadah dan kedisiplinan siswa dengan sistem yang presisi dan efisien. Mari wujudkan ekosistem spiritual yang terukur.
        </p>

        <div class="grid grid-cols-2 gap-6 pt-4">
          <div class="p-6 rounded-xl bg-surface-container-low">
            <span class="material-symbols-outlined text-primary text-3xl mb-3">speed</span>
            <h3 class="font-headline font-semibold text-on-surface">Efisiensi Tinggi</h3>
            <p class="text-sm text-on-surface-variant">Input data kehadiran dalam hitungan detik.</p>
          </div>
          <div class="p-6 rounded-xl bg-surface-container-low">
            <span class="material-symbols-outlined text-secondary text-3xl mb-3">analytics</span>
            <h3 class="font-headline font-semibold text-on-surface">Data Real-time</h3>
            <p class="text-sm text-on-surface-variant">Laporan otomatis yang akurat dan transparan.</p>
          </div>
        </div>
      </div>

      <!-- Right Side: Login Card -->
      <div class="flex justify-center lg:justify-end">
        <div class="w-full max-w-md glass-panel p-10 rounded-[2rem] shadow-2xl border border-white/20">
          <div class="mb-10 text-center lg:text-left">
            <h2 class="text-3xl font-headline font-bold text-on-surface mb-2">Login Petugas</h2>
            <p class="text-on-surface-variant font-body">Masukkan kredensial Anda untuk mengakses panel.</p>
          </div>

          <!-- Error message -->
          <div v-if="errorMsg" class="mb-4 px-4 py-3 bg-error/10 border border-error/20 rounded-xl text-error text-sm font-medium flex items-center gap-2">
            <span class="material-symbols-outlined text-lg">error</span>
            {{ errorMsg }}
          </div>
          
          <form class="space-y-6" @submit.prevent="handleLogin">
            <div class="space-y-2">
              <label class="block text-sm font-label font-semibold text-on-surface-variant ml-1" for="username">Username</label>
              <div class="relative">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline-variant">person</span>
                <input v-model="username" required class="w-full pl-12 pr-4 py-4 bg-surface-container-highest rounded-xl border-none focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all placeholder:text-outline" id="username" placeholder="Masukkan username" type="text"/>
              </div>
            </div>
            
            <div class="space-y-2">
              <label class="block text-sm font-label font-semibold text-on-surface-variant ml-1" for="password">Kata Sandi</label>
              <div class="relative">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline-variant">lock</span>
                <input v-model="password" required class="w-full pl-12 pr-12 py-4 bg-surface-container-highest rounded-xl border-none focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all placeholder:text-outline" id="password" placeholder="••••••••" :type="showPassword ? 'text' : 'password'"/>
                <button @click="showPassword = !showPassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-outline-variant hover:text-primary transition-colors" type="button">
                  <span class="material-symbols-outlined">{{ showPassword ? 'visibility_off' : 'visibility' }}</span>
                </button>
              </div>
            </div>

            <button :disabled="isLoading" class="w-full py-4 bg-primary text-on-primary rounded-full font-headline font-bold text-lg shadow-lg shadow-primary/20 hover:opacity-90 active:scale-[0.98] transition-all flex items-center justify-center gap-2 disabled:opacity-70" type="submit">
              <span v-if="isLoading" class="material-symbols-outlined animate-spin text-xl">progress_activity</span>
              <span v-else>Masuk</span>
              <span v-if="!isLoading" class="material-symbols-outlined">arrow_forward</span>
            </button>
          </form>

          <div class="mt-10 pt-8 border-t border-outline-variant/20 text-center">
            <p class="text-sm text-on-surface-variant">
              Belum punya akun? 
              <a @click.prevent="router.push('/register')" class="text-primary font-bold hover:underline cursor-pointer" href="#">Daftar Sekarang</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Visual Background Element -->
  <div class="fixed -bottom-24 -left-24 w-96 h-96 bg-primary/5 rounded-full blur-[120px] -z-10"></div>
  <div class="fixed -top-24 -right-24 w-96 h-96 bg-secondary/5 rounded-full blur-[120px] -z-10"></div>

  <!-- Footer -->
  <footer class="w-full py-8 mt-auto bg-[#f7f9fc]/90 dark:bg-slate-950 border-t border-slate-200/20 relative z-10 backdrop-blur-sm">
    <div class="flex flex-col md:flex-row justify-between items-center px-12 gap-4">
      <div class="font-bold text-slate-800 dark:text-slate-200 font-headline">
        e-Solat THP
      </div>
      <div class="text-slate-500 font-body text-xs">
        © 2024 e-Solat THP - The Serene Architect
      </div>
      <div class="flex gap-6">
        <a class="text-slate-500 font-body text-xs hover:text-[#fd9000] transition-colors" href="#">Kebijakan Privasi</a>
        <a class="text-slate-500 font-body text-xs hover:text-[#fd9000] transition-colors" href="#">Syarat &amp; Ketentuan</a>
        <a class="text-slate-500 font-body text-xs hover:text-[#fd9000] transition-colors" href="#">Kontak Kami</a>
      </div>
    </div>
  </footer>
</div>
</template>
