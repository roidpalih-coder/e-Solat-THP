<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const nis = ref('')
const showPopup = ref(false)
const isLoading = ref(false)
const errorMsg = ref('')
const absensiData = ref(null) // hasil dari API

const handleAbsen = async () => {
  if (!nis.value.trim()) return
  isLoading.value = true
  errorMsg.value = ''
  absensiData.value = null

  try {
    // Gunakan axios langsung (tanpa token auth) untuk absensi publik
    const res = await axios.post('/api/absensi/public', { nis: nis.value.trim() })
    absensiData.value = res.data.data
    showPopup.value = true
  } catch (err) {
    if (err.response?.status === 422) {
      errorMsg.value = err.response.data.message || 'NIS tidak valid atau sudah absen hari ini.'
    } else if (err.response?.status === 404) {
      errorMsg.value = 'NIS tidak ditemukan dalam sistem. Pastikan NIS sudah benar.'
    } else {
      errorMsg.value = 'Terjadi kesalahan. Pastikan koneksi internet dan coba lagi.'
    }
  } finally {
    isLoading.value = false
  }
}

const unshowPopup = () => {
  showPopup.value = false
  nis.value = ''
  absensiData.value = null
  errorMsg.value = ''
}

const goToLogin = () => {
  router.push('/login')
}

const formatTime = (timeStr) => {
  if (!timeStr) return '-'
  return timeStr.substring(0, 5) + ' WIB'
}

const getInitials = (name) => {
  if (!name) return '?'
  return name.split(' ').map(w => w[0]).join('').slice(0, 2).toUpperCase()
}
</script>

<template>
  <div class="bg-surface text-on-surface min-h-screen flex flex-col relative">
    <!-- TopNavBar -->
    <nav class="fixed top-0 w-full z-40 bg-[#f7f9fc]/80 dark:bg-slate-900/80 backdrop-blur-md shadow-sm dark:shadow-none flex justify-between items-center px-8 py-4 max-w-full">
      <div class="text-xl font-bold text-[#0050cb] dark:text-blue-400 font-headline cursor-pointer" @click="router.push('/')">e-Solat THP</div>
      <div class="hidden md:flex items-center gap-8">
        <a class="text-[#0050cb] font-headline font-semibold hover:text-primary/70 border-b-2 border-transparent transition-colors" href="#" @click.prevent="router.push('/')">Beranda</a>
        <a class="text-slate-600 dark:text-slate-400 hover:text-[#0050cb] transition-colors font-headline font-semibold" href="#">Tentang</a>
        <a class="text-slate-600 dark:text-slate-400 hover:text-[#0050cb] transition-colors font-headline font-semibold" href="#">Panduan</a>
      </div>
      <div class="flex items-center gap-4">
        <button @click="goToLogin" class="text-slate-600 dark:text-slate-400 font-headline font-semibold px-4 py-2 hover:text-[#0050cb] transition-colors">Masuk</button>
        <button @click="router.push('/register')" class="bg-primary text-on-primary px-6 py-2 rounded-full font-headline font-semibold hover:opacity-90 active:scale-95 transition-all">Daftar</button>
      </div>
    </nav>

    <main class="flex-grow flex items-center justify-center px-6 pt-20">
      <div class="max-w-[1400px] w-full grid grid-cols-1 lg:grid-cols-2 gap-12 items-center bg-surface-container rounded-[2rem] p-8 lg:p-16 relative overflow-hidden">
        <!-- Background Decorative -->
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-primary/5 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[30%] h-[30%] bg-secondary-container/10 rounded-full blur-[80px]"></div>

        <!-- Left Side: Content -->
        <div class="relative z-10 space-y-8">
          <div class="inline-flex items-center gap-2 px-4 py-2 bg-primary-container/10 text-primary rounded-full">
            <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">verified</span>
            <span class="text-xs font-bold tracking-wider uppercase">Portal Kehadiran Real-time</span>
          </div>
          <div class="space-y-4">
            <h1 class="text-5xl lg:text-6xl font-extrabold text-on-surface leading-tight tracking-tight headline">
              Absensi Solat
            </h1>
            <p class="text-lg text-on-surface-variant max-w-md leading-relaxed">
              Silakan lengkapi data kehadiran Anda dengan memasukkan Nomor Induk Siswa yang valid.
            </p>
          </div>
          <div class="space-y-6 pt-4">
            <div class="flex items-start gap-4">
              <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-surface-container-lowest shadow-sm flex items-center justify-center text-primary font-bold">1</div>
              <div>
                <h3 class="font-bold text-on-surface headline">Input NIS</h3>
                <p class="text-sm text-on-surface-variant">Masukkan Nomor Induk Siswa Anda pada kolom yang tersedia.</p>
              </div>
            </div>
            <div class="flex items-start gap-4">
              <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-surface-container-lowest shadow-sm flex items-center justify-center text-primary font-bold">2</div>
              <div>
                <h3 class="font-bold text-on-surface headline">Klik Tombol Absen</h3>
                <p class="text-sm text-on-surface-variant">Tekan tombol oranye bertuliskan 'ABSEN' untuk mengirim data.</p>
              </div>
            </div>
            <div class="flex items-start gap-4">
              <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-surface-container-lowest shadow-sm flex items-center justify-center text-primary font-bold">3</div>
              <div>
                <h3 class="font-bold text-on-surface headline">Konfirmasi Berhasil</h3>
                <p class="text-sm text-on-surface-variant">Tunggu pesan konfirmasi muncul untuk memastikan absensi tercatat.</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Side: Input Interface -->
        <div class="relative z-10">
          <div class="bg-surface-container-lowest rounded-[2.5rem] p-8 lg:p-12 shadow-[0_32px_64px_-16px_rgba(0,0,0,0.08)] flex flex-col items-center text-center">
            <div class="w-20 h-20 bg-surface-container rounded-3xl flex items-center justify-center mb-8">
              <span class="material-symbols-outlined text-4xl text-primary" style="font-variation-settings: 'FILL' 1;">person_pin_circle</span>
            </div>
            <h2 class="text-2xl font-bold text-on-surface mb-2 headline">Mulai Absensi</h2>
            <p class="text-sm text-on-surface-variant mb-10">Sistem akan memverifikasi identitas Anda secara otomatis.</p>

            <!-- Error Message -->
            <div v-if="errorMsg" class="w-full mb-6 px-4 py-3 bg-error/10 border border-error/20 rounded-xl text-error text-sm font-medium flex items-center gap-2 text-left">
              <span class="material-symbols-outlined text-lg flex-shrink-0">error</span>
              {{ errorMsg }}
            </div>

            <form class="w-full space-y-6" @submit.prevent="handleAbsen">
              <div class="space-y-2 text-left">
                <label class="text-xs font-bold text-on-surface-variant ml-1 uppercase tracking-widest font-label" for="nis">Nomor Induk Siswa (NIS)</label>
                <div class="relative group">
                  <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="material-symbols-outlined text-outline group-focus-within:text-primary transition-colors">badge</span>
                  </div>
                  <input v-model="nis" required class="w-full pl-12 pr-4 py-4 bg-surface-container-highest border-none rounded-xl text-on-surface placeholder:text-outline/60 focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all" id="nis" name="nis" placeholder="Contoh: 2024001928" type="text"/>
                </div>
              </div>
              <button :disabled="isLoading" class="w-full py-4 bg-secondary-container text-on-secondary-container font-bold text-lg rounded-full shadow-lg shadow-secondary-container/20 hover:translate-y-[-2px] active:scale-95 transition-all flex items-center justify-center gap-3 disabled:opacity-70" type="submit">
                <span v-if="isLoading" class="material-symbols-outlined animate-spin">progress_activity</span>
                <span v-else class="material-symbols-outlined">send</span>
                {{ isLoading ? 'MEMPROSES...' : 'ABSEN' }}
              </button>
            </form>
            <div class="mt-12 pt-8 border-t border-surface-container-high w-full">
              <p class="text-xs text-on-surface-variant">
                Butuh bantuan? Hubungi <a class="text-primary font-bold hover:underline" href="#">Petugas IT</a>
              </p>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Footer -->
    <footer class="w-full py-8 mt-auto bg-[#f7f9fc] dark:bg-slate-950 flex flex-col md:flex-row justify-between items-center px-12 border-t border-slate-200/20">
      <div class="font-bold text-slate-800 dark:text-slate-200 text-xs mb-4 md:mb-0">
        © 2024 e-Solat THP - The Serene Architect
      </div>
      <div class="flex gap-8 text-xs">
        <a class="text-slate-500 hover:text-[#fd9000] transition-colors" href="#">Kebijakan Privasi</a>
        <a class="text-slate-500 hover:text-[#fd9000] transition-colors" href="#">Syarat &amp; Ketentuan</a>
        <a class="text-slate-500 hover:text-[#fd9000] transition-colors" href="#">Kontak Kami</a>
      </div>
    </footer>

    <!-- Confirmation Popup -->
    <div v-if="showPopup" class="fixed inset-0 z-50 flex items-center justify-center p-4 glass-overlay">
      <div class="bg-surface-container-lowest w-full max-w-md rounded-[24px] shadow-[0_12px_32px_-4px_rgba(25,28,30,0.08)] overflow-hidden flex flex-col relative animate-in fade-in zoom-in duration-300">
        <!-- Success Header -->
        <div class="relative h-32 bg-gradient-to-br from-primary to-primary-container flex items-center justify-center">
          <div class="absolute top-0 right-0 p-6 opacity-10">
            <span class="material-symbols-outlined text-[120px]">check_circle</span>
          </div>
          <div class="bg-surface-container-lowest/20 backdrop-blur-md p-4 rounded-full">
            <span class="material-symbols-outlined text-white text-4xl" style="font-variation-settings: 'FILL' 1;">verified</span>
          </div>
        </div>
        <!-- Content Body -->
        <div class="p-8 text-center">
          <h1 class="headline text-2xl font-bold text-on-surface mb-2">Berhasil Absen!</h1>
          <p class="text-on-surface-variant text-sm mb-8">Data kehadiran siswa telah berhasil tercatat ke dalam sistem e-Solat.</p>

          <!-- Student Detail Card -->
          <div class="bg-surface-container-low rounded-xl p-5 mb-8 text-left space-y-3">
            <div class="flex items-center gap-4 mb-2">
              <div class="h-12 w-12 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-lg">
                {{ getInitials(absensiData?.siswa?.nama_siswa) }}
              </div>
              <div>
                <div class="text-xs font-label text-on-surface-variant uppercase tracking-wider">Nama Siswa</div>
                <div class="font-bold text-on-surface">{{ absensiData?.siswa?.nama_siswa || '-' }}</div>
              </div>
            </div>

            <div class="grid grid-cols-2 gap-4 pt-3 border-t border-outline-variant/20">
              <div>
                <div class="text-[10px] font-label text-on-surface-variant uppercase tracking-widest">NIS</div>
                <div class="text-sm font-semibold text-on-surface">{{ absensiData?.nis || nis }}</div>
              </div>
              <div>
                <div class="text-[10px] font-label text-on-surface-variant uppercase tracking-widest">Kelas</div>
                <div class="text-sm font-semibold text-on-surface">{{ absensiData?.siswa?.kelas?.nama_kelas || '-' }}</div>
              </div>
            </div>

            <div class="pt-3 border-t border-outline-variant/20 flex justify-between items-center">
              <div>
                <div class="text-[10px] font-label text-on-surface-variant uppercase tracking-widest">Waktu Absen</div>
                <div class="text-sm font-semibold text-on-surface">{{ formatTime(absensiData?.waktu_absen) }}</div>
              </div>
              <div class="bg-secondary-fixed text-on-secondary-fixed px-3 py-1 rounded-full text-[10px] font-bold">
                TEPAT WAKTU
              </div>
            </div>
          </div>

          <!-- Action Button -->
          <button @click="unshowPopup" class="w-full bg-secondary-container text-on-secondary-container font-headline font-bold py-4 rounded-full shadow-lg hover:brightness-95 active:scale-95 transition-all flex items-center justify-center gap-2">
            <span class="material-symbols-outlined text-xl">done_all</span>
            Selesai
          </button>
        </div>
        <!-- Footer Branding -->
        <div class="bg-surface-container-low/50 py-3 text-center">
          <span class="text-[10px] font-label text-on-surface-variant tracking-widest uppercase">e-Solat THP - The Serene Architect</span>
        </div>
      </div>
    </div>
  </div>
</template>
