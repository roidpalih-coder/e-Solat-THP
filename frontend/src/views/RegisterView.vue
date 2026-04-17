<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/services/api'

const router = useRouter()

const namaPetugas = ref('')
const username = ref('')
const password = ref('')
const passwordConfirmation = ref('')
const showPassword = ref(false)
const showPasswordConfirm = ref(false)
const isLoading = ref(false)
const errorMsg = ref('')
const successMsg = ref('')

const handleRegister = async () => {
  errorMsg.value = ''
  successMsg.value = ''

  if (password.value !== passwordConfirmation.value) {
    errorMsg.value = 'Password dan konfirmasi password tidak cocok.'
    return
  }
  if (password.value.length < 6) {
    errorMsg.value = 'Password minimal 6 karakter.'
    return
  }

  isLoading.value = true
  try {
    await api.post('/register', {
      nama_petugas: namaPetugas.value,
      username: username.value,
      password: password.value,
      password_confirmation: passwordConfirmation.value,
    })
    successMsg.value = 'Registrasi berhasil! Silakan login.'
    setTimeout(() => router.push('/login'), 1500)
  } catch (err) {
    if (err.response && err.response.data && err.response.data.errors) {
      const errors = err.response.data.errors
      const firstError = Object.values(errors)[0]
      errorMsg.value = Array.isArray(firstError) ? firstError[0] : firstError
    } else if (err.response && err.response.data && err.response.data.message) {
      errorMsg.value = err.response.data.message
    } else {
      errorMsg.value = 'Terjadi kesalahan. Coba lagi.'
    }
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
<div class="bg-surface font-body text-on-surface min-h-screen flex flex-col">
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
        <button @click="router.push('/register')" class="bg-primary text-on-primary px-6 py-2 rounded-full font-headline font-semibold scale-95 duration-200 active:opacity-80">Daftar</button>
        <button @click="router.push('/login')" class="text-slate-600 dark:text-slate-400 font-headline font-semibold hover:text-[#0050cb] transition-colors">Masuk</button>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <main class="flex-grow flex items-center justify-center pt-20 px-4 md:px-12">
    <div class="max-w-[1440px] w-full grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
      <!-- Left Side -->
      <div class="space-y-8 lg:pr-16">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-secondary/10 text-secondary font-label text-sm font-semibold">
          <span class="material-symbols-outlined text-sm">manage_accounts</span>
          Pendaftaran Petugas
        </div>
        <h1 class="text-5xl lg:text-7xl font-headline font-bold text-on-surface tracking-tight leading-[1.1]">
          Daftar sebagai <br/>
          <span class="text-primary">Petugas THP.</span>
        </h1>
        <p class="text-lg text-on-surface-variant leading-relaxed max-w-xl">
          Buat akun petugas untuk mengelola absensi solat siswa. Hanya petugas resmi yang dapat mendaftar ke sistem ini.
        </p>
        <div class="grid grid-cols-2 gap-6 pt-4">
          <div class="p-6 rounded-xl bg-surface-container-low">
            <span class="material-symbols-outlined text-primary text-3xl mb-3">security</span>
            <h3 class="font-headline font-semibold text-on-surface">Akses Aman</h3>
            <p class="text-sm text-on-surface-variant">Sistem otentikasi JWT yang terjamin.</p>
          </div>
          <div class="p-6 rounded-xl bg-surface-container-low">
            <span class="material-symbols-outlined text-secondary text-3xl mb-3">admin_panel_settings</span>
            <h3 class="font-headline font-semibold text-on-surface">Panel Admin</h3>
            <p class="text-sm text-on-surface-variant">Kelola semua data siswa dan absensi.</p>
          </div>
        </div>
      </div>

      <!-- Right Side: Register Card -->
      <div class="flex justify-center lg:justify-end">
        <div class="w-full max-w-md glass-panel p-10 rounded-[2rem] shadow-2xl border border-white/20">
          <div class="mb-8 text-center lg:text-left">
            <h2 class="text-3xl font-headline font-bold text-on-surface mb-2">Daftar Petugas</h2>
            <p class="text-on-surface-variant font-body">Isi data di bawah untuk membuat akun petugas.</p>
          </div>

          <!-- Alert Messages -->
          <div v-if="errorMsg" class="mb-4 px-4 py-3 bg-error/10 border border-error/20 rounded-xl text-error text-sm font-medium flex items-center gap-2">
            <span class="material-symbols-outlined text-lg">error</span>
            {{ errorMsg }}
          </div>
          <div v-if="successMsg" class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm font-medium flex items-center gap-2">
            <span class="material-symbols-outlined text-lg">check_circle</span>
            {{ successMsg }}
          </div>

          <form class="space-y-5" @submit.prevent="handleRegister">
            <!-- Nama Petugas -->
            <div class="space-y-2">
              <label class="block text-sm font-label font-semibold text-on-surface-variant ml-1" for="nama_petugas">Nama Lengkap</label>
              <div class="relative">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline-variant">badge</span>
                <input v-model="namaPetugas" required class="w-full pl-12 pr-4 py-4 bg-surface-container-highest rounded-xl border-none focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all placeholder:text-outline" id="nama_petugas" placeholder="Nama lengkap petugas" type="text"/>
              </div>
            </div>

            <!-- Username -->
            <div class="space-y-2">
              <label class="block text-sm font-label font-semibold text-on-surface-variant ml-1" for="reg_username">Username</label>
              <div class="relative">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline-variant">person</span>
                <input v-model="username" required class="w-full pl-12 pr-4 py-4 bg-surface-container-highest rounded-xl border-none focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all placeholder:text-outline" id="reg_username" placeholder="Buat username unik" type="text"/>
              </div>
            </div>

            <!-- Password -->
            <div class="space-y-2">
              <label class="block text-sm font-label font-semibold text-on-surface-variant ml-1" for="reg_password">Kata Sandi</label>
              <div class="relative">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline-variant">lock</span>
                <input v-model="password" required class="w-full pl-12 pr-12 py-4 bg-surface-container-highest rounded-xl border-none focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all placeholder:text-outline" id="reg_password" placeholder="Min. 6 karakter" :type="showPassword ? 'text' : 'password'"/>
                <button @click="showPassword = !showPassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-outline-variant hover:text-primary transition-colors" type="button">
                  <span class="material-symbols-outlined">{{ showPassword ? 'visibility_off' : 'visibility' }}</span>
                </button>
              </div>
            </div>

            <!-- Konfirmasi Password -->
            <div class="space-y-2">
              <label class="block text-sm font-label font-semibold text-on-surface-variant ml-1" for="reg_password_confirm">Konfirmasi Kata Sandi</label>
              <div class="relative">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline-variant">lock_reset</span>
                <input v-model="passwordConfirmation" required class="w-full pl-12 pr-12 py-4 bg-surface-container-highest rounded-xl border-none focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all placeholder:text-outline" id="reg_password_confirm" placeholder="Ulangi kata sandi" :type="showPasswordConfirm ? 'text' : 'password'"/>
                <button @click="showPasswordConfirm = !showPasswordConfirm" class="absolute right-4 top-1/2 -translate-y-1/2 text-outline-variant hover:text-primary transition-colors" type="button">
                  <span class="material-symbols-outlined">{{ showPasswordConfirm ? 'visibility_off' : 'visibility' }}</span>
                </button>
              </div>
            </div>

            <button :disabled="isLoading" class="w-full py-4 bg-primary text-on-primary rounded-full font-headline font-bold text-lg shadow-lg shadow-primary/20 hover:opacity-90 active:scale-[0.98] transition-all flex items-center justify-center gap-2 disabled:opacity-70" type="submit">
              <span v-if="isLoading" class="material-symbols-outlined animate-spin text-xl">progress_activity</span>
              <span v-else>Daftar Sekarang</span>
              <span v-if="!isLoading" class="material-symbols-outlined">how_to_reg</span>
            </button>
          </form>

          <div class="mt-8 pt-6 border-t border-outline-variant/20 text-center">
            <p class="text-sm text-on-surface-variant">
              Sudah punya akun?
              <a @click.prevent="router.push('/login')" class="text-primary font-bold hover:underline cursor-pointer" href="#">Login di sini</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Visual Background -->
  <div class="fixed -bottom-24 -left-24 w-96 h-96 bg-primary/5 rounded-full blur-[120px] -z-10"></div>
  <div class="fixed -top-24 -right-24 w-96 h-96 bg-secondary/5 rounded-full blur-[120px] -z-10"></div>

  <!-- Footer -->
  <footer class="w-full py-8 mt-auto bg-[#f7f9fc] dark:bg-slate-950 border-t border-slate-200/20">
    <div class="flex flex-col md:flex-row justify-between items-center px-12 gap-4">
      <div class="font-bold text-slate-800 dark:text-slate-200 font-headline">e-Solat THP</div>
      <div class="text-slate-500 font-body text-xs">© 2024 e-Solat THP - The Serene Architect</div>
      <div class="flex gap-6">
        <a class="text-slate-500 font-body text-xs hover:text-[#fd9000] transition-colors" href="#">Kebijakan Privasi</a>
        <a class="text-slate-500 font-body text-xs hover:text-[#fd9000] transition-colors" href="#">Syarat &amp; Ketentuan</a>
      </div>
    </div>
  </footer>
</div>
</template>
