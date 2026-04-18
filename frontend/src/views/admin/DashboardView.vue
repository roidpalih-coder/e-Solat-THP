<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/services/api'

const router = useRouter()

// --- State ---
const siswaList = ref([])
const kelasList = ref([])
const absensiHariIni = ref([])
const weeklyStats = ref([])
const isLoading = ref(true)

// --- Computed Stats ---
const totalSiswa = computed(() => siswaList.value.length)
const totalKelas = computed(() => kelasList.value.length)
const totalAbsensiHariIni = computed(() => absensiHariIni.value.length)

// Gunakan timezone Asia/Jakarta agar cocok dengan backend (tanpa peduli timezone user)
const today = computed(() => {
  return new Intl.DateTimeFormat('en-CA', { timeZone: 'Asia/Jakarta' }).format(new Date())
})

const todayFormatted = computed(() => {
  return new Date().toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' })
})

// Aktivitas terbaru (5 absensi terakhir)
const recentActivity = computed(() => absensiHariIni.value.slice(0, 5))

// Persentase kehadiran hari ini
const persentaseHadir = computed(() => {
  if (!totalSiswa.value) return 0
  return Math.round((totalAbsensiHariIni.value / totalSiswa.value) * 100)
})

// Data Chart Dinamis (Sen-Jum)
const chartData = computed(() => {
  const days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab']
  const targetDays = [1, 2, 3, 4, 5] // Senin - Jumat

  let maxTotal = 0;
  const mapData = { 1: 0, 2: 0, 3: 0, 4: 0, 5: 0 }
  
  weeklyStats.value.forEach(item => {
    const d = new Date(item.tanggal)
    const dayOfWeek = d.getDay()
    if (mapData[dayOfWeek] !== undefined) {
      mapData[dayOfWeek] += item.total
    }
  })

  Object.values(mapData).forEach(val => {
    if (val > maxTotal) maxTotal = val
  })

  maxTotal = Math.max(maxTotal, 1)

  return targetDays.map(d => ({
    dayName: days[d],
    total: mapData[d],
    heightPercent: Math.max((mapData[d] / maxTotal) * 100, 10),
    isToday: new Date().getDay() === d
  }))
})

// --- Fetch Data ---
const fetchData = async () => {
  isLoading.value = true
  try {
    const [siswaRes, kelasRes, absensiRes, weeklyRes] = await Promise.all([
      api.get('/siswa'),
      api.get('/kelas'),
      api.get(`/absensi/by-date/${today.value}`),
      api.get('/absensi/weekly'),
    ])
    siswaList.value = siswaRes.data.data || []
    kelasList.value = kelasRes.data.data || []
    absensiHariIni.value = absensiRes.data.data || []
    weeklyStats.value = weeklyRes.data.data || []
  } catch (err) {
    console.error('Gagal fetch data dashboard:', err)
  } finally {
    isLoading.value = false
  }
}

const getInitials = (name) => {
  if (!name) return '?'
  return name.split(' ').map(w => w[0]).join('').slice(0, 2).toUpperCase()
}

const formatTime = (timeStr) => {
  if (!timeStr) return '-'
  return timeStr.substring(0, 5) + ' WIB'
}

onMounted(fetchData)
</script>

<template>
  <div class="p-8 lg:p-12">
    <!-- Header Section -->
    <header class="flex justify-between items-end mb-10">
      <div>
        <h2 class="text-3xl lg:text-4xl font-bold font-headline text-on-surface mb-2">Dashboard Summary</h2>
        <p class="text-on-surface-variant font-body">Berikut adalah ringkasan data e-Solat THP hari ini.</p>
      </div>
      <div class="flex gap-4">
        <div class="flex items-center gap-2 bg-surface-container-low px-4 py-2 rounded-full border border-outline-variant/10">
          <span class="material-symbols-outlined text-primary" style="font-size: 20px;">calendar_today</span>
          <span class="text-sm font-medium text-on-surface-variant">{{ todayFormatted }}</span>
        </div>
      </div>
    </header>

    <!-- Loading State -->
    <div v-if="isLoading" class="flex items-center justify-center py-20">
      <div class="flex flex-col items-center gap-4">
        <span class="material-symbols-outlined text-primary text-5xl animate-spin">progress_activity</span>
        <p class="text-on-surface-variant">Memuat data...</p>
      </div>
    </div>

    <template v-else>
      <!-- Statistics Bento Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Stat Card 1: Jumlah Siswa -->
        <div class="bg-surface-container-lowest p-6 rounded-2xl shadow-sm border border-transparent hover:shadow-md transition-shadow">
          <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 bg-primary-fixed flex items-center justify-center rounded-xl text-primary">
              <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">person</span>
            </div>
            <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">Aktif</span>
          </div>
          <p class="text-on-surface-variant text-sm font-medium mb-1">Jumlah Siswa</p>
          <h3 class="text-3xl font-extrabold font-headline text-on-surface">{{ totalSiswa.toLocaleString('id-ID') }}</h3>
        </div>

        <!-- Stat Card 2: Jumlah Kelas -->
        <div class="bg-surface-container-lowest p-6 rounded-2xl shadow-sm border border-transparent hover:shadow-md transition-shadow">
          <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 bg-secondary-fixed flex items-center justify-center rounded-xl text-secondary">
              <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">meeting_room</span>
            </div>
            <span class="text-xs font-bold text-on-surface-variant bg-surface-container px-2 py-1 rounded-lg">Kelas</span>
          </div>
          <p class="text-on-surface-variant text-sm font-medium mb-1">Jumlah Kelas</p>
          <h3 class="text-3xl font-extrabold font-headline text-on-surface">{{ totalKelas }}</h3>
        </div>

        <!-- Stat Card 3: Persentase Hadir -->
        <div class="bg-surface-container-lowest p-6 rounded-2xl shadow-sm border border-transparent hover:shadow-md transition-shadow">
          <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 bg-tertiary-fixed flex items-center justify-center rounded-xl text-tertiary">
              <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">percent</span>
            </div>
            <span class="text-xs font-bold text-on-surface-variant bg-surface-container px-2 py-1 rounded-lg">Hari Ini</span>
          </div>
          <p class="text-on-surface-variant text-sm font-medium mb-1">Persentase Hadir</p>
          <h3 class="text-3xl font-extrabold font-headline text-on-surface">{{ persentaseHadir }}%</h3>
        </div>

        <!-- Stat Card 4: Kehadiran Hari Ini -->
        <div class="bg-primary-container p-6 rounded-2xl shadow-lg border border-transparent relative overflow-hidden group">
          <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
          <div class="relative z-10 text-on-primary-container">
            <div class="flex justify-between items-start mb-4">
              <div class="w-12 h-12 bg-white/20 backdrop-blur-md flex items-center justify-center rounded-xl">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">verified_user</span>
              </div>
              <span class="text-xs font-bold bg-white/20 px-2 py-1 rounded-lg">Live</span>
            </div>
            <p class="text-on-primary-container/80 text-sm font-medium mb-1">Kehadiran Hari Ini</p>
            <h3 class="text-4xl font-extrabold font-headline">{{ totalAbsensiHariIni }}</h3>
            <p class="text-xs mt-4 font-medium opacity-70">dari {{ totalSiswa }} Siswa Terdaftar</p>
          </div>
        </div>
      </div>

      <!-- Content Split Section -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <!-- Detailed Attendance Chart Visual -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-8 shadow-sm">
          <div class="flex justify-between items-center mb-8">
            <div>
              <h4 class="text-xl font-bold font-headline">Grafik Kehadiran Mingguan</h4>
              <p class="text-sm text-on-surface-variant">Pemantauan partisipasi solat berjamaah</p>
            </div>
            <div class="flex gap-2">
              <button class="px-4 py-1.5 rounded-full text-xs font-bold bg-secondary-fixed text-on-secondary-fixed">Minggu Ini</button>
            </div>
          </div>
          <!-- Chart dynamic -->
          <div class="h-64 flex items-end justify-between gap-2 px-4">
            <div v-for="(col, index) in chartData" :key="index" class="flex flex-col items-center gap-2 flex-1 group relative">
              <div class="absolute -top-8 bg-surface-container-highest text-on-surface text-[10px] font-bold px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                {{ col.total }} Absen
              </div>
              <div 
                class="w-full rounded-t-lg transition-all"
                :class="col.isToday ? 'bg-primary-container shadow-lg shadow-primary-container/20 group-hover:brightness-110' : 'bg-surface-container-high group-hover:bg-primary/20'" 
                :style="`height: ${col.heightPercent}%`"
              ></div>
              <span class="text-[10px] font-bold" :class="col.isToday ? 'text-primary' : 'text-on-surface-variant'">{{ col.dayName }}</span>
            </div>
          </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-surface-container-low rounded-3xl p-6 h-full">
          <h4 class="text-lg font-bold font-headline mb-6">Absensi Terbaru</h4>

          <div v-if="recentActivity.length === 0" class="text-center py-8">
            <span class="material-symbols-outlined text-4xl text-on-surface-variant/40">event_busy</span>
            <p class="text-sm text-on-surface-variant mt-2">Belum ada absensi hari ini</p>
          </div>

          <div v-else class="space-y-4">
            <div v-for="(item, idx) in recentActivity" :key="item.id_absensi" class="flex gap-4">
              <div class="relative">
                <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-xs">
                  {{ getInitials(item.siswa?.nama_siswa) }}
                </div>
                <div v-if="idx < recentActivity.length - 1" class="absolute top-10 left-1/2 -translate-x-1/2 w-0.5 h-4 bg-outline-variant/30"></div>
              </div>
              <div>
                <p class="text-sm font-semibold text-on-surface">{{ item.siswa?.nama_siswa || 'Unknown' }}</p>
                <p class="text-xs text-on-surface-variant">{{ item.siswa?.kelas?.nama_kelas || '-' }}</p>
                <p class="text-[10px] mt-0.5 text-primary font-medium">{{ formatTime(item.waktu_absen) }}</p>
              </div>
            </div>
          </div>

          <div class="mt-6 pt-4 border-t border-outline-variant/20">
            <button @click="router.push('/admin/absensi-siswa')" class="w-full text-center text-xs font-bold text-primary hover:underline transition-all">
              Lihat Semua Absensi →
            </button>
          </div>

          <div class="mt-4">
            <div class="bg-white/50 rounded-2xl p-4">
              <p class="text-xs font-bold text-on-surface-variant uppercase mb-3">Pesan Sistem</p>
              <p class="text-sm text-on-surface leading-relaxed">
                Pastikan semua perangkat absensi aktif sebelum waktu solat Zuhur dimulai.
              </p>
            </div>
          </div>
        </div>
      </div>
    </template>
  </div>
</template>
