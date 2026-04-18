<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/services/api'

const router = useRouter()

// --- State ---
const siswaList = ref([])
const kelasList = ref([])
const isLoading = ref(false)

// Filter state
const searchQuery = ref('')
const filterGender = ref('') // '' | 'Laki-laki' | 'Perempuan'
const filterKelasId = ref(null)
const filterJurusan = ref('')

const jurusanList = computed(() => {
  const jurusans = new Set()
  kelasList.value.forEach(k => {
    const parts = k.nama_kelas.split(' ')
    if (parts.length >= 2) jurusans.add(parts[1])
  })
  return Array.from(jurusans).sort()
})

// Modal edit
const showEditModal = ref(false)
const editedStudent = ref({})
const isSaving = ref(false)
const editError = ref('')

// Delete confirm
const showDeleteConfirm = ref(false)
const deletingNis = ref(null)
const isDeleting = ref(false)

// --- Computed: filtered list ---
const filteredSiswa = computed(() => {
  let list = siswaList.value
  if (searchQuery.value.trim()) {
    const q = searchQuery.value.toLowerCase()
    list = list.filter(s =>
      s.nama_siswa?.toLowerCase().includes(q) ||
      s.nis?.includes(q)
    )
  }
  if (filterGender.value) {
    list = list.filter(s => s.jenis_kelamin === filterGender.value)
  }
  if (filterKelasId.value) {
    list = list.filter(s => s.id_kelas === filterKelasId.value)
  }
  if (filterJurusan.value) {
    list = list.filter(s => {
      const parts = s.kelas?.nama_kelas?.split(' ') || []
      return parts.length >= 2 && parts[1] === filterJurusan.value
    })
  }
  return list
})

// --- Methods ---
const fetchSiswa = async () => {
  isLoading.value = true
  try {
    const res = await api.get('/siswa')
    siswaList.value = res.data.data || []
  } catch (err) {
    console.error('Gagal fetch siswa:', err)
  } finally {
    isLoading.value = false
  }
}

const fetchKelas = async () => {
  try {
    const res = await api.get('/kelas')
    kelasList.value = res.data.data || []
  } catch (err) {
    console.error('Gagal fetch kelas:', err)
  }
}

const getInitials = (name) => {
  if (!name) return '?'
  return name.split(' ').map(w => w[0]).join('').slice(0, 2).toUpperCase()
}

// --- Edit ---
const openEditModal = (siswa) => {
  editedStudent.value = {
    nis: siswa.nis,
    nama_siswa: siswa.nama_siswa,
    jenis_kelamin: siswa.jenis_kelamin,
    id_kelas: siswa.id_kelas,
  }
  editError.value = ''
  showEditModal.value = true
}

const closeEditModal = () => {
  showEditModal.value = false
}

const saveEdit = async () => {
  isSaving.value = true
  editError.value = ''
  try {
    await api.put(`/siswa/${editedStudent.value.nis}`, {
      nama_siswa: editedStudent.value.nama_siswa,
      jenis_kelamin: editedStudent.value.jenis_kelamin,
      id_kelas: editedStudent.value.id_kelas,
    })
    showEditModal.value = false
    await fetchSiswa()
  } catch (err) {
    if (err.response?.data?.message) {
      editError.value = err.response.data.message
    } else {
      editError.value = 'Gagal menyimpan. Coba lagi.'
    }
  } finally {
    isSaving.value = false
  }
}

// --- Delete ---
const confirmDelete = (nis) => {
  deletingNis.value = nis
  showDeleteConfirm.value = true
}

const cancelDelete = () => {
  showDeleteConfirm.value = false
  deletingNis.value = null
}

const doDelete = async () => {
  isDeleting.value = true
  try {
    await api.delete(`/siswa/${deletingNis.value}`)
    showDeleteConfirm.value = false
    deletingNis.value = null
    await fetchSiswa()
  } catch (err) {
    console.error('Gagal hapus siswa:', err)
  } finally {
    isDeleting.value = false
  }
}

// --- Filter helpers ---
const setGender = (g) => { filterGender.value = filterGender.value === g ? '' : g }
const setKelas = (id) => { filterKelasId.value = filterKelasId.value === id ? null : id }

onMounted(async () => {
  await Promise.all([fetchSiswa(), fetchKelas()])
})
</script>

<template>
  <div class="flex flex-col h-full bg-surface">
    <!-- Header & Switch Tabs -->
    <header class="mb-10 px-8 pt-8">
      <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
          <h2 class="text-3xl font-extrabold text-on-surface font-headline mb-2 tracking-tight">Data Siswa</h2>
          <p class="text-on-surface-variant text-sm max-w-lg">Manajemen informasi siswa terpadu untuk pemantauan kehadiran dan kedisiplinan ibadah.</p>
        </div>
        <!-- Switch Buttons -->
        <div class="flex p-1.5 bg-surface-container-high rounded-full w-fit">
          <button class="px-6 py-2 bg-white text-primary shadow-sm rounded-full font-bold text-sm transition-all focus:outline-none">
            Data Siswa
          </button>
          <button @click="router.push('/admin/tambah-siswa')" class="px-6 py-2 text-on-surface-variant hover:text-primary rounded-full font-medium text-sm transition-all focus:outline-none">
            Tambah Data Siswa
          </button>
        </div>
      </div>
    </header>

    <div class="px-8 pb-8 flex-1 flex flex-col">
      <!-- Filter Section -->
      <section class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-8">
        <!-- Search -->
        <div class="lg:col-span-2 p-6 bg-surface-container-lowest rounded-xl shadow-sm flex flex-col gap-4">
          <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-primary">search</span>
            <h3 class="font-bold text-sm text-on-surface">Cari Siswa</h3>
          </div>
          <div class="flex-1 relative">
            <input v-model="searchQuery" class="w-full bg-surface-container-low border-none rounded-lg py-2.5 px-4 text-sm focus:ring-2 focus:ring-primary/20 transition-all placeholder:text-outline" placeholder="Cari Nama atau NIS..." type="text"/>
          </div>
        </div>

        <!-- Kelas Filter -->
        <div class="p-6 bg-surface-container-lowest rounded-xl shadow-sm">
          <div class="flex items-center gap-3 mb-4">
            <span class="material-symbols-outlined text-primary">layers</span>
            <h3 class="font-bold text-sm text-on-surface">Kelas</h3>
          </div>
          <div class="flex flex-wrap gap-2">
            <button @click="setKelas(null)" :class="filterKelasId === null ? 'bg-secondary-fixed text-on-secondary-fixed font-bold' : 'bg-surface-container-high text-on-surface-variant font-medium'" class="px-3 py-1.5 rounded-full text-xs transition-all hover:bg-slate-200">Semua</button>
            <button
              v-for="kelas in kelasList"
              :key="kelas.id_kelas"
              @click="setKelas(kelas.id_kelas)"
              :class="filterKelasId === kelas.id_kelas ? 'bg-secondary-fixed text-on-secondary-fixed font-bold' : 'bg-surface-container-high text-on-surface-variant font-medium'"
              class="px-3 py-1.5 rounded-full text-xs transition-all hover:bg-slate-200"
            >
              {{ kelas.nama_kelas }}
            </button>
          </div>
        </div>

        <!-- Gender Filter -->
        <div class="p-6 bg-surface-container-lowest rounded-xl shadow-sm">
          <div class="flex items-center gap-3 mb-4">
            <span class="material-symbols-outlined text-primary">wc</span>
            <h3 class="font-bold text-sm text-on-surface">Gender</h3>
          </div>
          <div class="flex flex-wrap gap-2">
            <button @click="setGender('')" :class="filterGender === '' ? 'bg-secondary-fixed text-on-secondary-fixed font-bold' : 'bg-surface-container-high text-on-surface-variant font-medium'" class="px-3 py-1.5 rounded-full text-xs transition-all hover:bg-slate-200">Semua</button>
            <button @click="setGender('Laki-laki')" :class="filterGender === 'Laki-laki' ? 'bg-secondary-fixed text-on-secondary-fixed font-bold' : 'bg-surface-container-high text-on-surface-variant font-medium'" class="px-3 py-1.5 rounded-full text-xs transition-all hover:bg-slate-200">Laki-laki</button>
            <button @click="setGender('Perempuan')" :class="filterGender === 'Perempuan' ? 'bg-secondary-fixed text-on-secondary-fixed font-bold' : 'bg-surface-container-high text-on-surface-variant font-medium'" class="px-3 py-1.5 rounded-full text-xs transition-all hover:bg-slate-200">Perempuan</button>
          </div>
        </div>

        <!-- Jurusan Filter -->
        <div class="p-6 bg-surface-container-lowest rounded-xl shadow-sm">
          <div class="flex items-center gap-3 mb-4">
            <span class="material-symbols-outlined text-primary">school</span>
            <h3 class="font-bold text-sm text-on-surface">Jurusan</h3>
          </div>
          <div class="flex flex-wrap gap-2">
            <button @click="filterJurusan = ''" :class="filterJurusan === '' ? 'bg-secondary-fixed text-on-secondary-fixed font-bold' : 'bg-surface-container-high text-on-surface-variant font-medium'" class="px-3 py-1.5 rounded-full text-xs transition-all hover:bg-slate-200">Semua</button>
            <button
              v-for="jurusan in jurusanList"
              :key="jurusan"
              @click="filterJurusan = filterJurusan === jurusan ? '' : jurusan"
              :class="filterJurusan === jurusan ? 'bg-secondary-fixed text-on-secondary-fixed font-bold' : 'bg-surface-container-high text-on-surface-variant font-medium'"
              class="px-3 py-1.5 rounded-full text-xs transition-all hover:bg-slate-200"
            >
              {{ jurusan }}
            </button>
          </div>
        </div>
      </section>

      <!-- Student Table Section -->
      <div class="bg-surface-container-lowest rounded-2xl shadow-sm overflow-hidden flex-1 flex flex-col mb-10">
        <div class="p-6 border-b border-surface-container flex items-center justify-between">
          <h3 class="text-xl font-bold font-headline text-on-surface">
            Daftar Siswa
            <span class="ml-2 text-sm font-normal text-on-surface-variant">({{ filteredSiswa.length }} data)</span>
          </h3>
          <div class="flex gap-2">
            <span v-if="isLoading" class="material-symbols-outlined text-primary animate-spin">progress_activity</span>
          </div>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead class="bg-surface-container-low">
              <tr>
                <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">No</th>
                <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">NIS</th>
                <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Nama Siswa</th>
                <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Jenis Kelamin</th>
                <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Kelas</th>
                <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-surface-container">
              <!-- Empty state -->
              <tr v-if="filteredSiswa.length === 0 && !isLoading">
                <td colspan="6" class="px-8 py-12 text-center">
                  <span class="material-symbols-outlined text-4xl text-on-surface-variant/40 block mb-2">person_search</span>
                  <p class="text-on-surface-variant text-sm">Tidak ada data siswa yang cocok.</p>
                </td>
              </tr>
              <!-- Loading state -->
              <tr v-if="isLoading">
                <td colspan="6" class="px-8 py-12 text-center">
                  <span class="material-symbols-outlined text-4xl text-primary animate-spin block mb-2">progress_activity</span>
                  <p class="text-on-surface-variant text-sm">Memuat data siswa...</p>
                </td>
              </tr>
              <!-- Data rows -->
              <tr v-for="(siswa, idx) in filteredSiswa" :key="siswa.nis" class="hover:bg-surface-container-low/50 transition-colors group">
                <td class="px-6 py-4 text-sm font-medium text-on-surface-variant">{{ idx + 1 }}</td>
                <td class="px-6 py-4 text-sm font-semibold text-primary">{{ siswa.nis }}</td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xs flex-shrink-0">
                      {{ getInitials(siswa.nama_siswa) }}
                    </div>
                    <span class="text-sm font-bold text-on-surface">{{ siswa.nama_siswa }}</span>
                  </div>
                </td>
                <td class="px-6 py-4 text-sm text-on-surface-variant">{{ siswa.jenis_kelamin }}</td>
                <td class="px-6 py-4">
                  <span class="px-2.5 py-1 bg-surface-container-high text-on-surface-variant rounded-md text-[11px] font-bold">
                    {{ siswa.kelas?.nama_kelas || '-' }}
                  </span>
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button @click="openEditModal(siswa)" class="p-1.5 text-primary hover:bg-primary/10 rounded-lg transition-all" title="Edit">
                      <span class="material-symbols-outlined text-[20px]">edit</span>
                    </button>
                    <button @click="confirmDelete(siswa.nis)" class="p-1.5 text-error hover:bg-error/10 rounded-lg transition-all" title="Hapus">
                      <span class="material-symbols-outlined text-[20px]">delete</span>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- Pagination Footer -->
        <div class="mt-auto p-6 border-t border-surface-container flex flex-col md:flex-row justify-between items-center gap-4">
          <span class="text-xs text-on-surface-variant font-medium">
            Menampilkan <span class="font-bold text-on-surface">{{ filteredSiswa.length }}</span> dari <span class="font-bold text-on-surface">{{ siswaList.length }}</span> Siswa
          </span>
        </div>
      </div>
    </div>

    <!-- Floating Action Button -->
    <button @click="router.push('/admin/tambah-siswa')" class="fixed bottom-24 right-8 w-14 h-14 bg-primary text-white rounded-full shadow-lg hover:scale-105 active:scale-95 transition-all flex items-center justify-center z-30 group">
      <span class="material-symbols-outlined text-[28px]">person_add</span>
      <span class="absolute right-16 bg-on-surface text-white text-[10px] py-1 px-3 rounded-md whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">Tambah Siswa</span>
    </button>

    <!-- Modal Edit Siswa -->
    <div v-if="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 glass-overlay">
      <div class="bg-surface-container-lowest w-full max-w-md rounded-[24px] shadow-2xl p-8 relative animate-in fade-in zoom-in duration-300">
        <h2 class="text-2xl font-bold text-on-surface font-headline mb-6">Edit Data Siswa</h2>

        <div v-if="editError" class="mb-4 px-4 py-3 bg-error/10 border border-error/20 rounded-xl text-error text-sm font-medium">
          {{ editError }}
        </div>

        <form @submit.prevent="saveEdit" class="space-y-4">
          <div class="flex flex-col gap-2">
            <label class="text-xs font-semibold text-on-surface-variant ml-1 uppercase tracking-wider">NIS</label>
            <input :value="editedStudent.nis" disabled class="w-full bg-surface-container-high border-none rounded-lg px-4 py-3 text-on-surface-variant font-medium cursor-not-allowed" type="text"/>
          </div>
          <div class="flex flex-col gap-2">
            <label class="text-xs font-semibold text-on-surface-variant ml-1 uppercase tracking-wider">Nama Lengkap</label>
            <input v-model="editedStudent.nama_siswa" required class="w-full bg-surface-container-highest border-none focus:ring-2 focus:ring-primary/20 rounded-lg px-4 py-3 text-on-surface font-medium" type="text"/>
          </div>
          <div class="flex flex-col gap-2">
            <label class="text-xs font-semibold text-on-surface-variant ml-1 uppercase tracking-wider">Jenis Kelamin</label>
            <select v-model="editedStudent.jenis_kelamin" class="w-full bg-surface-container-highest border-none focus:ring-2 focus:ring-primary/20 rounded-lg px-4 py-3 text-on-surface font-medium appearance-none">
              <option value="Laki-laki">Laki-laki</option>
              <option value="Perempuan">Perempuan</option>
            </select>
          </div>
          <div class="flex flex-col gap-2">
            <label class="text-xs font-semibold text-on-surface-variant ml-1 uppercase tracking-wider">Kelas</label>
            <select v-model="editedStudent.id_kelas" class="w-full bg-surface-container-highest border-none focus:ring-2 focus:ring-primary/20 rounded-lg px-4 py-3 text-on-surface font-medium appearance-none">
              <option v-for="kelas in kelasList" :key="kelas.id_kelas" :value="kelas.id_kelas">{{ kelas.nama_kelas }}</option>
            </select>
          </div>
          <div class="flex justify-end gap-3 pt-6">
            <button @click="closeEditModal" class="px-6 py-2.5 rounded-full font-semibold text-on-surface-variant hover:bg-surface-container" type="button">Batal</button>
            <button :disabled="isSaving" class="px-6 py-2.5 rounded-full bg-primary text-white font-bold hover:brightness-110 shadow-md active:scale-95 transition-all disabled:opacity-70 flex items-center gap-2" type="submit">
              <span v-if="isSaving" class="material-symbols-outlined animate-spin text-sm">progress_activity</span>
              Simpan Perubahan
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div v-if="showDeleteConfirm" class="fixed inset-0 z-50 flex items-center justify-center p-4 glass-overlay">
      <div class="bg-surface-container-lowest w-full max-w-sm rounded-[24px] shadow-2xl p-8 animate-in fade-in zoom-in duration-300">
        <div class="flex flex-col items-center text-center gap-4">
          <div class="w-16 h-16 bg-error/10 rounded-full flex items-center justify-center">
            <span class="material-symbols-outlined text-error text-3xl">delete_forever</span>
          </div>
          <h2 class="text-xl font-bold text-on-surface font-headline">Hapus Siswa?</h2>
          <p class="text-sm text-on-surface-variant">Data siswa dengan NIS <strong>{{ deletingNis }}</strong> akan dihapus permanen dan tidak dapat dikembalikan.</p>
          <div class="flex gap-3 w-full pt-2">
            <button @click="cancelDelete" class="flex-1 px-6 py-2.5 rounded-full font-semibold text-on-surface-variant hover:bg-surface-container border">Batal</button>
            <button @click="doDelete" :disabled="isDeleting" class="flex-1 px-6 py-2.5 rounded-full bg-error text-white font-bold hover:brightness-110 active:scale-95 transition-all disabled:opacity-70 flex items-center justify-center gap-2">
              <span v-if="isDeleting" class="material-symbols-outlined animate-spin text-sm">progress_activity</span>
              <span v-else>Hapus</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
