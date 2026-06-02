<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { useTheme } from '@/stores/theme'

const router = useRouter()
const { isDark, toggleTheme } = useTheme()

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

// State for the interactive physical console simulation
const terminalInput = ref('')
const terminalStatus = ref('READY') // 'READY', 'SUCCESS', 'ERROR'
const terminalStatusText = ref('SIAP UNTUK ABSENSI')
const isTerminalSuccess = ref(false)

const handleTerminalKeyPress = (key) => {
  if (terminalStatus.value === 'SUCCESS') return // Block keys during success state

  if (key === 'C') {
    terminalInput.value = ''
    terminalStatus.value = 'READY'
    terminalStatusText.value = 'SIAP UNTUK ABSENSI'
  } else if (key === 'NIS') {
    if (terminalInput.value.length < 4) {
      terminalStatus.value = 'ERROR'
      terminalStatusText.value = 'NIS MINIMAL 4 DIGIT'
      setTimeout(() => {
        if (terminalStatus.value === 'ERROR') {
          terminalStatus.value = 'READY'
          terminalStatusText.value = 'SIAP UNTUK ABSENSI'
        }
      }, 2000)
    } else {
      terminalStatus.value = 'SUCCESS'
      terminalStatusText.value = 'NIS DITERIMA'
      isTerminalSuccess.value = true
      
      // Reset success state after 2.5 seconds
      setTimeout(() => {
        terminalInput.value = ''
        terminalStatus.value = 'READY'
        terminalStatusText.value = 'SIAP UNTUK ABSENSI'
        isTerminalSuccess.value = false
      }, 2500)
    }
  } else {
    // Append number up to 10 digits
    if (terminalInput.value.length < 10) {
      terminalInput.value += key
    }
  }
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
        <!-- Theme Toggle -->
        <button @click="toggleTheme" class="w-10 h-10 rounded-full flex items-center justify-center text-slate-600 dark:text-slate-400 hover:bg-surface-container transition-colors cursor-pointer" :title="isDark ? 'Mode Terang' : 'Mode Gelap'">
          <span class="material-symbols-outlined">{{ isDark ? 'light_mode' : 'dark_mode' }}</span>
        </button>
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
            <p class="text-on-surface-variant leading-relaxed mb-2">Pencatatan kehadiran menggunakan QR Code atau biometrik yang terintegrasi langsung dengan database pusat.</p>
          </div>
          <div class="group hover:bg-surface-container transition-colors p-8 rounded-3xl border border-outline-variant/10">
            <div class="w-16 h-16 rounded-2xl bg-secondary/10 flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
              <span class="material-symbols-outlined text-secondary text-3xl">badge</span>
            </div>
            <h3 class="text-2xl font-bold text-on-surface mb-4 headline">Manajemen Siswa</h3>
            <p class="text-on-surface-variant leading-relaxed mb-2">Database lengkap yang mencakup profil, rekam jejak kehadiran, dan perkembangan spiritual setiap individu.</p>
          </div>
          <div class="group hover:bg-surface-container transition-colors p-8 rounded-3xl border border-outline-variant/10">
            <div class="w-16 h-16 rounded-2xl bg-tertiary/10 flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
              <span class="material-symbols-outlined text-tertiary text-3xl">analytics</span>
            </div>
            <h3 class="text-2xl font-bold text-on-surface mb-4 headline">Laporan Real-time</h3>
            <p class="text-on-surface-variant leading-relaxed mb-2">Dashboard analytics untuk admin dan orang tua siswa guna memantau kedisiplinan ibadah secara instan.</p>
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
                  <h4 class="text-xl font-bold mb-2 headline">Registrasi Akun / Data Siswa</h4>
                  <p class="text-on-surface-variant">Lakukan pendaftaran institusi dan pastikan seluruh data NIS serta nama siswa sudah diinput secara massal oleh admin melalui panel dashboard.</p>
                </div>
              </div>
              <div class="flex gap-6">
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center font-bold text-xl headline">2</div>
                <div>
                  <h4 class="text-xl font-bold mb-2 headline">Input NIS Kehadiran</h4>
                  <p class="text-on-surface-variant">Siswa datang ke area absensi dan mengetikkan Nomor Induk Siswa (NIS) mereka masing-masing pada perangkat absensi yang disediakan untuk mencatat kehadiran secara real-time.</p>
                </div>
              </div>
              <div class="flex gap-6">
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center font-bold text-xl headline">3</div>
                <div>
                  <h4 class="text-xl font-bold mb-2 headline">Pantau &amp; Evaluasi</h4>
                  <p class="text-on-surface-variant">Admin dan staf pengajar menerima laporan harian, mingguan, dan bulanan secara otomatis berdasarkan rekapitulasi input NIS tersebut.</p>
                </div>
              </div>
            </div>
          </div>
          <div class="lg:w-1/2 w-full flex items-center justify-center">
            <!-- Blurred UI Panel wrapping the 3D terminal simulation -->
            <div class="relative bg-surface-container-highest/60 backdrop-blur-md rounded-[2.5rem] p-6 w-full max-w-md mx-auto shadow-2xl border border-white/15 aspect-square flex flex-col justify-between overflow-hidden">
              
              <!-- 3D Perspective Wrapper for the Physical Terminal Console -->
              <div class="relative w-full h-full flex flex-col justify-center items-center py-2" style="perspective: 1000px;">
                <div 
                  class="w-full max-w-[340px] bg-gradient-to-b from-[#2d3748] to-[#1a202c] rounded-[2rem] p-5 shadow-[0_20px_50px_rgba(0,0,0,0.5),_inset_0_2px_4px_rgba(255,255,255,0.15)] border-t border-[#4a5568] transition-all duration-300 relative select-none"
                  style="transform: rotateX(8deg) rotateY(-4deg); transform-style: preserve-3d;"
                  :class="{ 'ring-4 ring-emerald-500/50 shadow-[0_0_30px_rgba(16,185,129,0.4)]': terminalStatus === 'SUCCESS' }"
                >
                  <!-- Glossy Screen Bezel -->
                  <div class="bg-[#0f172a] rounded-xl p-3 border border-[#334155] shadow-inner mb-4">
                    <!-- LCD Screen -->
                    <div 
                      class="rounded-lg p-3 font-mono text-sm relative overflow-hidden h-24 flex flex-col justify-between transition-colors duration-300"
                      :class="{
                        'bg-[#061f1a] text-[#10b981] shadow-[inset_0_0_15px_rgba(16,185,129,0.2)]': terminalStatus === 'READY',
                        'bg-[#022c22] text-[#34d399] shadow-[inset_0_0_20px_rgba(52,211,153,0.4)]': terminalStatus === 'SUCCESS',
                        'bg-[#2d0606] text-[#f87171] shadow-[inset_0_0_15px_rgba(248,113,113,0.3)]': terminalStatus === 'ERROR'
                      }"
                    >
                      <!-- Scan lines overlay for retro terminal look -->
                      <div class="absolute inset-0 bg-scanlines opacity-5 pointer-events-none"></div>
                      
                      <!-- Screen Header -->
                      <div class="flex justify-between items-center text-[10px] opacity-80 border-b border-current/20 pb-1">
                        <span>KONSOL ABSENSI v2.0</span>
                        <span class="flex items-center gap-1">
                          <span 
                            class="w-1.5 h-1.5 rounded-full inline-block"
                            :class="{
                              'bg-green-500 animate-pulse': terminalStatus === 'READY',
                              'bg-emerald-400 animate-ping': terminalStatus === 'SUCCESS',
                              'bg-red-500 animate-bounce': terminalStatus === 'ERROR'
                            }"
                          ></span>
                          SIAP
                        </span>
                      </div>
                      
                      <!-- Screen NIS Value -->
                      <div class="my-2 flex items-center gap-1 text-base font-bold">
                        <span>NIS:</span>
                        <template v-if="terminalInput">
                          <span class="tracking-wider">{{ terminalInput }}</span>
                        </template>
                        <template v-else>
                          <span class="opacity-40 font-normal text-sm italic">Input NIS...</span>
                        </template>
                        <!-- Blinking cursor -->
                        <span class="w-2.5 h-4 bg-current inline-block animate-blink"></span>
                      </div>
                      
                      <!-- Screen Footer Status -->
                      <div class="text-[10px] text-right font-semibold uppercase tracking-wider">
                        {{ terminalStatusText }}
                      </div>
                    </div>
                  </div>

                  <!-- Keypad Container -->
                  <div class="bg-[#1e293b] rounded-xl p-3 border border-[#334155]/85 shadow-inner">
                    <div class="grid grid-cols-3 gap-2">
                      <!-- Numbers 1-9 -->
                      <button 
                        v-for="num in ['1','2','3','4','5','6','7','8','9']" 
                        :key="num"
                        @click="handleTerminalKeyPress(num)"
                        class="h-9 bg-gradient-to-b from-[#4a5568] to-[#2d3748] active:from-[#2d3748] active:to-[#1a202c] text-white font-mono font-bold rounded-lg border-b-4 border-slate-900 shadow-md active:translate-y-[2px] active:border-b-0 hover:brightness-110 active:scale-95 transition-all select-none flex items-center justify-center cursor-pointer"
                      >
                        {{ num }}
                      </button>

                      <!-- Clear Button -->
                      <button 
                        @click="handleTerminalKeyPress('C')"
                        class="h-9 bg-gradient-to-b from-red-600 to-red-700 active:from-red-700 active:to-red-800 text-white font-mono font-bold rounded-lg border-b-4 border-slate-900 shadow-md active:translate-y-[2px] active:border-b-0 hover:brightness-110 active:scale-95 transition-all select-none flex items-center justify-center cursor-pointer"
                      >
                        C
                      </button>

                      <!-- Zero -->
                      <button 
                        @click="handleTerminalKeyPress('0')"
                        class="h-9 bg-gradient-to-b from-[#4a5568] to-[#2d3748] active:from-[#2d3748] active:to-[#1a202c] text-white font-mono font-bold rounded-lg border-b-4 border-slate-900 shadow-md active:translate-y-[2px] active:border-b-0 hover:brightness-110 active:scale-95 transition-all select-none flex items-center justify-center cursor-pointer"
                      >
                        0
                      </button>

                      <!-- Special NIS Key (Prominent & Colored) -->
                      <button 
                        @click="handleTerminalKeyPress('NIS')"
                        class="h-9 bg-gradient-to-b from-primary to-[#0050cb] active:from-[#0050cb] active:to-primary text-white font-sans font-extrabold rounded-lg border-b-4 border-slate-900 shadow-[0_0_10px_rgba(0,80,203,0.3)] active:translate-y-[2px] active:border-b-0 hover:brightness-110 active:scale-95 transition-all select-none flex items-center justify-center cursor-pointer"
                      >
                        NIS
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Green Notification Popup (Right Top) -->
              <div 
                class="absolute -top-3 -right-3 glass-panel p-5 rounded-2xl shadow-lg border border-white/30 hidden md:block transition-all duration-500 z-30"
                :class="{ 
                  'scale-105 bg-emerald-500/90 text-white border-emerald-400/50 shadow-emerald-500/20 translate-y-[-4px]': isTerminalSuccess,
                  'scale-100 bg-[#f7f9fc]/80 dark:bg-slate-900/80 text-slate-800 dark:text-slate-100': !isTerminalSuccess 
                }"
              >
                <div class="flex items-center gap-3">
                  <span 
                    class="material-symbols-outlined transition-colors duration-300"
                    :class="isTerminalSuccess ? 'text-white' : 'text-green-500'"
                    style="font-variation-settings: 'FILL' 1;"
                  >
                    check_circle
                  </span>
                  <p class="font-bold text-sm select-none">Input NIS Berhasil!</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>
  </div>
</template>

<style scoped>
@keyframes blink {
  0%, 49% { opacity: 1; }
  50%, 100% { opacity: 0; }
}

.animate-blink {
  animation: blink 1s step-end infinite;
}

.bg-scanlines {
  background: linear-gradient(
    rgba(18, 16, 16, 0) 50%, 
    rgba(0, 0, 0, 0.25) 50%
  );
  background-size: 100% 4px;
}
</style>
