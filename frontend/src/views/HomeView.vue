<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()

const stats = ref({
  total_siswa: 0,
  total_absen_hari_ini: 0
})

const fetchStats = async () => {
  try {
    const res = await axios.get('/api/stats')
    stats.value = res.data.data
  } catch (err) {
    console.error('Failed to fetch public stats', err)
  }
}

onMounted(() => {
  fetchStats()
})

const goToLogin = () => {
  router.push('/login')
}

const goToAbsensi = () => {
  router.push('/absensi')
}

const scrollToTop = () => {
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

const scrollTo = (selector) => {
  const el = document.querySelector(selector)
  if (el) el.scrollIntoView({ behavior: 'smooth' })
}
</script>

<template>
  <div class="bg-surface text-on-surface selection:bg-primary-fixed selection:text-on-primary-fixed">
    <!-- TopNavBar -->
    <nav class="fixed top-0 w-full z-50 bg-[#f7f9fc]/80 dark:bg-slate-900/80 backdrop-blur-md shadow-sm dark:shadow-none flex justify-between items-center px-8 py-4 max-w-full">
      <div class="flex items-center gap-2 cursor-pointer" @click="scrollToTop">
        <img src="/images/Thp_logo.png" alt="Logo SMK THP" class="h-10" />
        <span class="hidden md:inline text-xl font-bold text-[#0050cb] dark:text-blue-400">e-Solat THP</span>
      </div>
      <div class="hidden md:flex items-center gap-8 font-['Plus_Jakarta_Sans'] font-semibold">
        <a @click.prevent="scrollToTop" class="text-[#0050cb] border-b-2 border-[#0050cb] pb-1 cursor-pointer">Beranda</a>
        <a @click.prevent="scrollTo('#fitur')" class="text-slate-600 dark:text-slate-400 hover:text-[#0050cb] transition-colors cursor-pointer">Tentang</a>
        <a @click.prevent="scrollTo('#panduan')" class="text-slate-600 dark:text-slate-400 hover:text-[#0050cb] transition-colors cursor-pointer">Panduan</a>
      </div>
      <div class="flex items-center gap-4">
        <button @click="goToLogin" class="text-slate-600 dark:text-slate-400 font-semibold px-4 py-2 hover:text-[#0050cb] transition-colors cursor-pointer">Masuk</button>
        <button @click="router.push('/register')" class="bg-primary text-on-primary px-6 py-2 rounded-full font-semibold shadow-md active:scale-95 duration-200 cursor-pointer">Daftar</button>
      </div>
    </nav>
    <main class="pt-24">
      <!-- Hero Section -->
      <section class="relative min-h-[870px] flex items-center px-8 md:px-16 lg:px-24 overflow-hidden">
        <div class="absolute inset-0 z-0">
          <div class="absolute top-[-10%] right-[-10%] w-[600px] h-[600px] rounded-full bg-primary/5 blur-[120px]"></div>
          <div class="absolute bottom-[-10%] left-[-10%] w-[400px] h-[400px] rounded-full bg-secondary-container/10 blur-[100px]"></div>
        </div>
        <div class="relative z-10 w-full grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
          <div class="max-w-2xl">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-fixed text-on-primary-fixed mb-6">
              <span class="material-symbols-outlined text-sm">verified</span>
              <span class="text-xs font-bold tracking-wider uppercase">Sistem Terintegrasi v2.0</span>
            </div>
            <h1 class="text-5xl md:text-7xl font-extrabold text-on-surface leading-tight mb-6 tracking-tight headline">
              Sistem Absensi <br/>
              <span class="text-primary bg-clip-text text-transparent bg-gradient-to-r from-primary to-primary-container">Solat Digital</span>
            </h1>
            <p class="text-lg md:text-xl text-on-surface-variant mb-10 leading-relaxed font-body">
              Harmonisasi spiritual dan efisiensi administratif. Kelola kehadiran ibadah siswa dengan transparansi penuh dan laporan real-time yang akurat dalam satu genggaman.
            </p>
            <div class="flex flex-wrap gap-4">
              <button @click="goToLogin" class="bg-primary text-on-primary px-10 py-4 rounded-full font-bold text-lg flex items-center gap-3 shadow-lg shadow-primary/20 hover:translate-y-[-2px] transition-all">
                Mulai
                <span class="material-symbols-outlined">rocket_launch</span>
              </button>
              <button @click="goToAbsensi" class="bg-secondary-container text-on-secondary-container px-10 py-4 rounded-full font-bold text-lg flex items-center gap-3 shadow-lg shadow-secondary-container/20 hover:translate-y-[-2px] transition-all">
                Absensi
                <span class="material-symbols-outlined">how_to_reg</span>
              </button>
            </div>
          </div>
          <div class="hidden lg:block relative">
            <div class="relative z-20 rounded-[2rem] overflow-hidden shadow-2xl transform rotate-3 hover:rotate-0 transition-transform duration-700 bg-surface-container-lowest p-4">
              <img alt="Digital Attendance" class="rounded-[1.5rem] w-full h-[500px] object-cover" src="/images/Desktop.jpeg"/>
              <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent pointer-events-none"></div>
              <div class="absolute bottom-8 left-8 right-8 glass-panel p-6 rounded-2xl border border-white/20">
                <div class="flex justify-between items-center">
                  <div>
                    <p class="text-on-surface text-sm font-semibold">Kehadiran Terkini</p>
                    <p class="text-primary text-2xl font-bold">98.5%</p>
                  </div>
                  <div class="bg-primary-container p-3 rounded-full">
                    <span class="material-symbols-outlined text-white">trending_up</span>
                  </div>
                </div>
              </div>
            </div>
            <!-- Decorative back elements -->
            <div class="absolute top-8 left-8 w-full h-full border-2 border-primary/20 rounded-[2rem] z-10"></div>
          </div>
        </div>
      </section>
      <!-- Stats Section -->
      <section class="px-8 md:px-16 lg:px-24 py-20 bg-surface-container-low">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="bg-surface-container-lowest p-8 rounded-3xl shadow-sm flex flex-col gap-4">
            <span class="material-symbols-outlined text-primary text-4xl">group</span>
            <div>
              <h3 class="text-4xl font-extrabold text-on-surface headline">{{ stats.total_siswa.toLocaleString('id-ID') }}</h3>
              <p class="text-on-surface-variant font-medium">Siswa Terdaftar</p>
            </div>
          </div>
          <div class="bg-surface-container-lowest p-8 rounded-3xl shadow-sm flex flex-col gap-4 md:translate-y-4">
            <span class="material-symbols-outlined text-secondary text-4xl">verified_user</span>
            <div>
              <h3 class="text-4xl font-extrabold text-on-surface headline">{{ stats.total_absen_hari_ini.toLocaleString('id-ID') }}</h3>
              <p class="text-on-surface-variant font-medium">Siswa Sudah Absen</p>
            </div>
          </div>
          <div class="bg-surface-container-lowest p-8 rounded-3xl shadow-sm flex flex-col gap-4">
            <span class="material-symbols-outlined text-tertiary text-4xl">update</span>
            <div>
              <h3 class="text-4xl font-extrabold text-on-surface headline">Real-time</h3>
              <p class="text-on-surface-variant font-medium">Pelaporan Absensi</p>
            </div>
          </div>
        </div>
      </section>
      <!-- Fitur Utama -->
      <section id="fitur" class="px-8 md:px-16 lg:px-24 py-32">
        <div class="mb-20 text-center max-w-3xl mx-auto">
          <h2 class="headline text-4xl font-bold text-on-surface mb-6">Fitur Utama</h2>
          <p class="text-on-surface-variant text-lg">Inovasi teknologi yang dirancang khusus untuk memudahkan pengawasan ibadah secara holistik dan transparan.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
          <div class="group hover:bg-surface-container transition-colors p-8 rounded-3xl border border-outline-variant/10">
            <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
              <span class="material-symbols-outlined text-primary text-3xl">qr_code_2</span>
            </div>
            <h3 class="text-2xl font-bold text-on-surface mb-4 headline">Absensi Solat Digital</h3>
            <p class="text-on-surface-variant leading-relaxed mb-6">Pencatatan kehadiran menggunakan QR Code atau biometrik yang terintegrasi langsung dengan database pusat.</p>
            <a class="text-primary font-bold inline-flex items-center gap-2 hover:gap-4 transition-all" href="#">
              Selengkapnya <span class="material-symbols-outlined">arrow_forward</span>
            </a>
          </div>
          <div class="group hover:bg-surface-container transition-colors p-8 rounded-3xl border border-outline-variant/10">
            <div class="w-16 h-16 rounded-2xl bg-secondary/10 flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
              <span class="material-symbols-outlined text-secondary text-3xl">badge</span>
            </div>
            <h3 class="text-2xl font-bold text-on-surface mb-4 headline">Manajemen Siswa</h3>
            <p class="text-on-surface-variant leading-relaxed mb-6">Database lengkap yang mencakup profil, rekam jejak kehadiran, dan perkembangan spiritual setiap individu.</p>
            <a class="text-primary font-bold inline-flex items-center gap-2 hover:gap-4 transition-all" href="#">
              Selengkapnya <span class="material-symbols-outlined">arrow_forward</span>
            </a>
          </div>
          <div class="group hover:bg-surface-container transition-colors p-8 rounded-3xl border border-outline-variant/10">
            <div class="w-16 h-16 rounded-2xl bg-tertiary/10 flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
              <span class="material-symbols-outlined text-tertiary text-3xl">analytics</span>
            </div>
            <h3 class="text-2xl font-bold text-on-surface mb-4 headline">Laporan Real-time</h3>
            <p class="text-on-surface-variant leading-relaxed mb-6">Dashboard analytics untuk admin dan orang tua siswa guna memantau kedisiplinan ibadah secara instan.</p>
            <a class="text-primary font-bold inline-flex items-center gap-2 hover:gap-4 transition-all" href="#">
              Selengkapnya <span class="material-symbols-outlined">arrow_forward</span>
            </a>
          </div>
        </div>
      </section>
      <!-- Cara Menggunakan -->
      <section id="panduan" class="px-8 md:px-16 lg:px-24 py-32 bg-surface-container-low">
        <div class="flex flex-col lg:flex-row gap-16 items-center">
          <div class="lg:w-1/2">
            <h2 class="headline text-4xl font-bold text-on-surface mb-12">Cara Menggunakan Sistem</h2>
            <div class="space-y-12">
              <div class="flex gap-6">
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center font-bold text-xl headline">1</div>
                <div>
                  <h4 class="text-xl font-bold mb-2 headline">Registrasi Akun</h4>
                  <p class="text-on-surface-variant">Lakukan pendaftaran institusi dan masukkan data siswa secara massal melalui panel dashboard admin.</p>
                </div>
              </div>
              <div class="flex gap-6">
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center font-bold text-xl headline">2</div>
                <div>
                  <h4 class="text-xl font-bold mb-2 headline">Scanning Kehadiran</h4>
                  <p class="text-on-surface-variant">Siswa melakukan scan QR Code yang tersedia di area ibadah menggunakan aplikasi mobile e-Solat.</p>
                </div>
              </div>
              <div class="flex gap-6">
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center font-bold text-xl headline">3</div>
                <div>
                  <h4 class="text-xl font-bold mb-2 headline">Pantau &amp; Evaluasi</h4>
                  <p class="text-on-surface-variant">Admin dan staf pengajar menerima laporan harian, mingguan, dan bulanan secara otomatis.</p>
                </div>
              </div>
            </div>
          </div>
          <div class="lg:w-1/2 w-full">
            <div class="relative bg-surface-container-highest rounded-3xl p-2 aspect-square max-w-md mx-auto shadow-xl">
              <img alt="Tutorial Illustration" class="w-full h-full object-cover rounded-2xl" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCaZQ0Cp1lUcuTW8iKKGDVax6LCyeAll5mtn2l3K3HVpWQa0W2WFwS8PFhsHGn0fK6mRh9lcbWMsrPREXp3GZuHtm9gFO0u0ADPjET7eEL-zf9tpT_GNgC3hkZZKGlLv4yxY9MV0RU1tALiVFqh8tJHtvYRdNqzsnU0HJ65mFWgaLBX_y6SE4kp7eg_Bh5JmYY-nl8sKVLeFOsLFmXfsihzTwK4KFEdL2qmdW7igmSlS6qUppeBJnsjvYiSJWtzX2I2NX9CkXwBUq8"/>
              <div class="absolute -top-6 -right-6 glass-panel p-6 rounded-2xl shadow-lg border border-white/30 hidden md:block">
                <div class="flex items-center gap-3">
                  <span class="material-symbols-outlined text-green-500" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                  <p class="font-bold text-sm">Scan Berhasil!</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>
  </div>
</template>
