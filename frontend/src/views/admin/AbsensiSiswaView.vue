<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue'
import api from '@/services/api'

// --- State ---
const kelasList = ref([])
const absensiList = ref([])
const selectedDate = ref(new Intl.DateTimeFormat('en-CA', { timeZone: 'Asia/Jakarta' }).format(new Date()))
const selectedKelasId = ref(null)
const searchQuery = ref('')
const isLoading = ref(false)
let pollingInterval = null

// --- Computed ---
const todayFormatted = computed(() => {
  return new Date().toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' })
})

const filteredAbsensi = computed(() => {
  let list = absensiList.value
  // Filter kelas
  if (selectedKelasId.value) {
    list = list.filter(a => a.siswa?.kelas?.id_kelas === selectedKelasId.value)
  }
  // Filter pencarian nama
  if (searchQuery.value.trim()) {
    const q = searchQuery.value.toLowerCase()
    list = list.filter(a =>
      a.siswa?.nama_siswa?.toLowerCase().includes(q) ||
      a.nis?.includes(q)
    )
  }
  return list
})

const totalAbsensi = computed(() => absensiList.value.length)

const absensiByKelas = computed(() => {
  if (!selectedKelasId.value) return absensiList.value.length
  return filteredAbsensi.value.length
})

const persentasi = computed(() => {
  if (!totalSiswaKelas.value) return 0
  return Math.round((filteredAbsensi.value.length / totalSiswaKelas.value) * 100)
})

const totalSiswaKelas = computed(() => {
  if (!selectedKelasId.value) return 1
  const kelas = kelasList.value.find(k => k.id_kelas === selectedKelasId.value)
  return kelas?.siswa?.length || 1
})

// --- Methods ---
const fetchKelas = async () => {
  try {
    const res = await api.get('/kelas')
    kelasList.value = res.data.data || []
  } catch (err) {
    console.error('Gagal fetch kelas:', err)
  }
}

const fetchAbsensi = async () => {
  isLoading.value = true
  try {
    const res = await api.get(`/absensi/by-date/${selectedDate.value}`)
    absensiList.value = res.data.data || []
  } catch (err) {
    console.error('Gagal fetch absensi:', err)
    absensiList.value = []
  } finally {
    isLoading.value = false
  }
}

const selectKelas = (kelasId) => {
  selectedKelasId.value = selectedKelasId.value === kelasId ? null : kelasId
}

const getInitials = (name) => {
  if (!name) return '?'
  return name.split(' ').map(w => w[0]).join('').slice(0, 2).toUpperCase()
}

const formatTime = (timeStr) => {
  if (!timeStr) return '-'
  return timeStr.substring(0, 5) + ' WIB'
}

// Re-fetch saat tanggal berubah
watch(selectedDate, fetchAbsensi)

// Auto-refresh setiap 30 detik
onMounted(async () => {
  await fetchKelas()
  await fetchAbsensi()
  pollingInterval = setInterval(fetchAbsensi, 30000)
})

onUnmounted(() => {
  if (pollingInterval) clearInterval(pollingInterval)
})
</script>

<template>
  <div class="p-8">
    <!-- Header & Breadcrumbs -->
    <header class="mb-10">
      <div class="flex justify-between items-end mb-2">
        <div>
          <span class="text-xs font-bold text-primary uppercase tracking-widest mb-1 block">Staff Overview</span>
          <h2 class="text-3xl font-bold font-headline text-on-surface">Laporan Absensi Siswa</h2>
        </div>
        <div class="flex items-center gap-2 text-on-surface-variant text-sm border bg-surface-container-low px-4 py-2 rounded-full">
          <span class="material-symbols-outlined text-sm">calendar_today</span>
          <span class="font-medium">{{ todayFormatted }}</span>
        </div>
      </div>
    </header>

    <!-- Top Section: Daftar Kelas & Filter -->
    <section class="mb-8">
      <div class="bg-surface-container-lowest p-6 rounded-lg shadow-sm">
        <div class="flex items-center justify-between mb-6">
          <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-secondary">filter_alt</span>
            <h3 class="text-lg font-semibold text-on-surface">Filter Kelas</h3>
          </div>
          <div class="flex items-center gap-4 bg-surface-container-low px-4 py-2 rounded-xl border border-surface-container/50">
            <label class="text-xs font-bold text-on-surface-variant" for="view_date">PILIH TANGGAL</label>
            <input v-model="selectedDate" class="bg-transparent border-none focus:ring-0 text-sm font-semibold text-primary" id="view_date" type="date"/>
          </div>
        </div>
        <div class="flex flex-wrap gap-3">
          <!-- Tombol Semua -->
          <button
            @click="selectKelas(null)"
            :class="selectedKelasId === null ? 'bg-secondary-container text-on-secondary-container font-bold shadow-sm' : 'bg-surface-container-high text-on-surface-variant font-medium'"
            class="px-6 py-2 rounded-full text-sm hover:translate-y-[-2px] transition-all"
          >
            Semua Kelas
          </button>
          <!-- Tombol per Kelas -->
          <button
            v-for="kelas in kelasList"
            :key="kelas.id_kelas"
            @click="selectKelas(kelas.id_kelas)"
            :class="selectedKelasId === kelas.id_kelas ? 'bg-secondary-container text-on-secondary-container font-bold shadow-sm' : 'bg-surface-container-high text-on-surface-variant font-medium'"
            class="px-6 py-2 rounded-full text-sm hover:translate-y-[-2px] transition-all"
          >
            {{ kelas.nama_kelas }}
          </button>
        </div>
      </div>
    </section>

    <!-- Dashboard Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div class="bg-primary/5 p-6 rounded-lg border-l-4 border-primary">
        <p class="text-xs font-bold text-primary mb-1">TOTAL ABSEN HARI INI</p>
        <div class="flex items-end gap-2">
          <span class="text-3xl font-extrabold font-headline text-on-surface">{{ totalAbsensi }}</span>
          <span class="text-sm font-medium text-on-surface-variant mb-1">Siswa</span>
        </div>
      </div>
      <div class="bg-secondary/5 p-6 rounded-lg border-l-4 border-secondary-container">
        <p class="text-xs font-bold text-secondary-container mb-1">DITAMPILKAN</p>
        <div class="flex items-end gap-2">
          <span class="text-3xl font-extrabold font-headline text-on-surface">{{ filteredAbsensi.length }}</span>
          <span class="text-sm font-medium text-on-surface-variant mb-1">Siswa</span>
        </div>
      </div>
      <div class="bg-tertiary-fixed/30 p-6 rounded-lg border-l-4 border-tertiary">
        <p class="text-xs font-bold text-tertiary mb-1">AUTO-REFRESH</p>
        <div class="flex items-end gap-2">
          <span class="text-3xl font-extrabold font-headline text-on-surface">30s</span>
          <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse mb-2 ml-1"></div>
        </div>
      </div>
    </div>

    <!-- Main Area: Data Siswa yang sudah absen -->
    <section class="bg-surface-container-lowest rounded-xl overflow-hidden shadow-sm">
      <div class="px-8 py-6 border-b border-surface-container flex justify-between items-center bg-white">
        <div>
          <h3 class="text-lg font-bold text-on-surface">Data Siswa yang sudah absen</h3>
          <p class="text-xs text-on-surface-variant mt-1">
            {{ selectedDate }} · {{ filteredAbsensi.length }} siswa tercatat
          </p>
        </div>
        <div class="flex gap-2">
          <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline-variant text-sm">search</span>
            <input v-model="searchQuery" class="pl-10 pr-4 py-2 bg-surface-container-low border-none rounded-lg text-sm focus:ring-1 focus:ring-primary/20 w-64" placeholder="Cari nama atau NIS..." type="text"/>
          </div>
          <!-- Indikator loading -->
          <div v-if="isLoading" class="p-2 flex items-center">
            <span class="material-symbols-outlined text-primary animate-spin">progress_activity</span>
          </div>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-surface-container-low/50">
              <th class="px-8 py-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">No</th>
              <th class="px-6 py-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">NIS</th>
              <th class="px-6 py-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">Nama Siswa</th>
              <th class="px-6 py-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">Waktu Absen</th>
              <th class="px-6 py-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">Kelas</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-surface-container/30 border-b border-surface-container/30">
            <!-- Empty state -->
            <tr v-if="filteredAbsensi.length === 0">
              <td colspan="5" class="px-8 py-12 text-center">
                <span class="material-symbols-outlined text-4xl text-on-surface-variant/40 block mb-2">event_busy</span>
                <p class="text-on-surface-variant text-sm">Tidak ada data absensi untuk tanggal dan filter ini.</p>
              </td>
            </tr>
            <!-- Data rows -->
            <tr v-for="(item, idx) in filteredAbsensi" :key="item.id_absensi" class="hover:bg-surface-container-low/20 transition-colors">
              <td class="px-8 py-4 text-sm font-medium text-on-surface-variant">{{ String(idx + 1).padStart(2, '0') }}</td>
              <td class="px-6 py-4 text-sm font-mono text-on-surface">{{ item.nis }}</td>
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xs">
                    {{ getInitials(item.siswa?.nama_siswa) }}
                  </div>
                  <span class="text-sm font-semibold text-on-surface">{{ item.siswa?.nama_siswa || '-' }}</span>
                </div>
              </td>
              <td class="px-6 py-4">
                <span class="px-3 py-1 bg-surface-container-high text-primary rounded-full text-xs font-bold border border-primary/10">
                  {{ formatTime(item.waktu_absen) }}
                </span>
              </td>
              <td class="px-6 py-4 text-sm text-on-surface-variant">{{ item.siswa?.kelas?.nama_kelas || '-' }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="px-8 py-5 bg-surface-container-low flex items-center justify-between">
        <span class="text-xs text-on-surface-variant font-medium">
          Menampilkan <span class="font-bold text-on-surface">{{ filteredAbsensi.length }}</span> dari <span class="font-bold text-on-surface">{{ totalAbsensi }}</span> data absensi
        </span>
        <div class="flex items-center gap-2 text-xs text-on-surface-variant">
          <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
          Auto-refresh setiap 30 detik
        </div>
      </div>
    </section>
  </div>
</template>
