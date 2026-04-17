<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/services/api'

const router = useRouter()

const kelasList = ref([])
const totalSiswa = ref(0)
const isLoading = ref(false)
const isSubmitting = ref(false)
const successMsg = ref('')
const errorMsg = ref('')

// Form fields
const nis = ref('')
const namaSiswa = ref('')
const idKelas = ref('')
const jenisKelamin = ref('')

const fetchKelas = async () => {
  try {
    const res = await api.get('/kelas')
    kelasList.value = res.data.data || []
  } catch (err) {
    console.error('Gagal fetch kelas:', err)
  }
}

const fetchTotalSiswa = async () => {
  try {
    const res = await api.get('/siswa')
    totalSiswa.value = (res.data.data || []).length
  } catch (err) {
    console.error('Gagal fetch total siswa:', err)
  }
}

const handleSubmit = async () => {
  errorMsg.value = ''
  successMsg.value = ''

  if (!jenisKelamin.value) {
    errorMsg.value = 'Pilih jenis kelamin terlebih dahulu.'
    return
  }
  if (!idKelas.value) {
    errorMsg.value = 'Pilih kelas terlebih dahulu.'
    return
  }

  isSubmitting.value = true
  try {
    await api.post('/siswa', {
      nis: nis.value,
      nama_siswa: namaSiswa.value,
      jenis_kelamin: jenisKelamin.value,
      id_kelas: parseInt(idKelas.value),
    })
    successMsg.value = `Siswa "${namaSiswa.value}" berhasil ditambahkan!`
    // Reset form
    nis.value = ''
    namaSiswa.value = ''
    idKelas.value = ''
    jenisKelamin.value = ''
    totalSiswa.value++
    // Redirect after 1.5s
    setTimeout(() => router.push('/admin/data-siswa'), 1500)
  } catch (err) {
    if (err.response?.data?.errors) {
      const errors = err.response.data.errors
      const firstErr = Object.values(errors)[0]
      errorMsg.value = Array.isArray(firstErr) ? firstErr[0] : firstErr
    } else if (err.response?.data?.message) {
      errorMsg.value = err.response.data.message
    } else {
      errorMsg.value = 'Terjadi kesalahan, coba lagi.'
    }
  } finally {
    isSubmitting.value = false
  }
}

const navigateToDataSiswa = () => {
  router.push('/admin/data-siswa')
}

onMounted(async () => {
  await Promise.all([fetchKelas(), fetchTotalSiswa()])
})
</script>

<template>
<div class="p-8">
  <header class="flex justify-between items-center mb-10">
    <div>
      <h1 class="text-3xl font-extrabold tracking-tight font-headline text-on-surface">Tambah Data Siswa</h1>
      <p class="text-on-surface-variant mt-1">Manajemen data peserta didik e-Solat THP</p>
    </div>
    <div class="flex items-center gap-4">
      <div class="bg-surface-container-low px-4 py-2 rounded-full flex items-center gap-2">
        <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
        <span class="text-xs font-semibold text-primary">Status: Sistem Aktif</span>
      </div>
    </div>
  </header>

  <!-- Alert Messages -->
  <div v-if="successMsg" class="mb-6 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm font-medium flex items-center gap-2">
    <span class="material-symbols-outlined">check_circle</span>
    {{ successMsg }}
  </div>
  <div v-if="errorMsg" class="mb-6 px-4 py-3 bg-error/10 border border-error/20 rounded-xl text-error text-sm font-medium flex items-center gap-2">
    <span class="material-symbols-outlined">error</span>
    {{ errorMsg }}
  </div>

  <div class="grid grid-cols-12 gap-6 items-start">
    <div class="col-span-12 lg:col-span-8 bg-surface-container-lowest rounded-xl p-8 shadow-[0_12px_32px_-4px_rgba(25,28,30,0.08)]">
      <form class="space-y-8" @submit.prevent="handleSubmit">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="flex flex-col gap-2">
            <label class="text-xs font-semibold text-on-surface-variant ml-1 uppercase tracking-wider" for="nis">Nomor Induk Siswa (NIS)</label>
            <input v-model="nis" class="bg-surface-container-highest border-none focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest rounded-lg px-4 py-3 text-on-surface transition-all" id="nis" required placeholder="Contoh: 2024001" type="text"/>
          </div>
          <div class="flex flex-col gap-2">
            <label class="text-xs font-semibold text-on-surface-variant ml-1 uppercase tracking-wider" for="nama">Nama Lengkap</label>
            <input v-model="namaSiswa" class="bg-surface-container-highest border-none focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest rounded-lg px-4 py-3 text-on-surface transition-all" id="nama" required placeholder="Masukkan nama sesuai ijazah" type="text"/>
          </div>

          <!-- Kelas dari API -->
          <div class="flex flex-col gap-2">
            <label class="text-xs font-semibold text-on-surface-variant ml-1 uppercase tracking-wider" for="kelas">Kelas</label>
            <div class="relative">
              <select v-model="idKelas" required class="w-full bg-surface-container-highest border-none focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest rounded-lg px-4 py-3 text-on-surface transition-all appearance-none" id="kelas">
                <option disabled value="">Pilih Kelas</option>
                <option v-for="kelas in kelasList" :key="kelas.id_kelas" :value="kelas.id_kelas">{{ kelas.nama_kelas }}</option>
              </select>
              <span class="material-symbols-outlined absolute right-3 top-3 pointer-events-none text-on-surface-variant">expand_more</span>
            </div>
          </div>
        </div>

        <div class="space-y-3">
          <label class="text-xs font-semibold text-on-surface-variant ml-1 uppercase tracking-wider">Jenis Kelamin</label>
          <div class="flex gap-4">
            <label class="flex-1 cursor-pointer group">
              <input v-model="jenisKelamin" class="sr-only peer" name="gender" type="radio" value="Laki-laki"/>
              <div class="flex items-center justify-between p-4 bg-surface-container-highest rounded-lg peer-checked:bg-secondary-fixed peer-checked:ring-2 peer-checked:ring-secondary/30 transition-all border-none">
                <span class="font-medium text-on-surface">Laki-laki</span>
                <span class="material-symbols-outlined text-on-surface-variant">male</span>
              </div>
            </label>
            <label class="flex-1 cursor-pointer group">
              <input v-model="jenisKelamin" class="sr-only peer" name="gender" type="radio" value="Perempuan"/>
              <div class="flex items-center justify-between p-4 bg-surface-container-highest rounded-lg peer-checked:bg-secondary-fixed peer-checked:ring-2 peer-checked:ring-secondary/30 transition-all border-none">
                <span class="font-medium text-on-surface">Perempuan</span>
                <span class="material-symbols-outlined text-on-surface-variant">female</span>
              </div>
            </label>
          </div>
        </div>

        <div class="flex justify-end pt-4 gap-4">
          <button @click="navigateToDataSiswa" class="px-8 py-3 rounded-full font-semibold text-primary hover:bg-surface-container-high transition-colors" type="button">
            Batal
          </button>
          <button :disabled="isSubmitting" class="bg-secondary-container text-on-secondary-container px-12 py-3 rounded-full font-bold shadow-lg hover:brightness-110 active:scale-95 transition-all flex items-center gap-2 disabled:opacity-70" type="submit">
            <span v-if="isSubmitting" class="material-symbols-outlined animate-spin text-lg">progress_activity</span>
            <span v-else>Tambah</span>
            <span v-if="!isSubmitting" class="material-symbols-outlined text-lg">person_add</span>
          </button>
        </div>
      </form>
    </div>

    <!-- Informational Sidebar Card -->
    <div class="col-span-12 lg:col-span-4 space-y-6">
      <div class="bg-primary bg-gradient-to-br from-primary to-primary-container p-6 rounded-xl text-on-primary shadow-xl">
        <h3 class="text-lg font-bold mb-3 flex items-center gap-2">
          <span class="material-symbols-outlined">info</span>
          Panduan Input
        </h3>
        <ul class="text-sm space-y-3 opacity-90">
          <li class="flex gap-2">
            <span class="font-bold">1.</span> Pastikan NIS unik dan belum terdaftar dalam sistem.
          </li>
          <li class="flex gap-2">
            <span class="font-bold">2.</span> Nama harus menggunakan Huruf Kapital di setiap awal kata.
          </li>
          <li class="flex gap-2">
            <span class="font-bold">3.</span> Pilih kelas yang sesuai dari daftar kelas yang tersedia.
          </li>
          <li class="flex gap-2">
            <span class="font-bold">4.</span> Untuk import banyak siswa, gunakan fitur Import Siswa di sidebar.
          </li>
        </ul>
      </div>

      <div class="bg-surface-container-lowest/80 backdrop-blur-md border border-outline-variant/20 p-6 rounded-xl relative overflow-hidden">
        <div class="absolute -right-4 -bottom-4 opacity-5">
          <span class="material-symbols-outlined text-9xl">school</span>
        </div>
        <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-widest mb-1">Total Terdata</p>
        <p class="text-4xl font-extrabold text-primary font-headline">{{ totalSiswa.toLocaleString('id-ID') }}</p>
        <p class="text-xs text-on-surface-variant mt-2">Siswa aktif terdaftar saat ini</p>
      </div>
    </div>
  </div>
</div>
</template>
