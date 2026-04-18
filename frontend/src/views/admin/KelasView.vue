<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/services/api'

const router = useRouter()

// --- State ---
const kelasList = ref([])
const isLoading = ref(false)

// Modal Add/Edit
const showModal = ref(false)
const modalMode = ref('add') // 'add' | 'edit'
const formKelas = ref({ id_kelas: null, nama_kelas: '' })
const isSaving = ref(false)
const formError = ref('')

// Delete confirm
const showDeleteConfirm = ref(false)
const deletingId = ref(null)
const deletingName = ref('')
const isDeleting = ref(false)

// --- Methods ---
const fetchKelas = async () => {
  isLoading.value = true
  try {
    const res = await api.get('/kelas')
    kelasList.value = res.data.data || []
  } catch (err) {
    console.error('Gagal fetch kelas:', err)
  } finally {
    isLoading.value = false
  }
}

// --- Add & Edit ---
const openAddModal = () => {
  modalMode.value = 'add'
  formKelas.value = { id_kelas: null, nama_kelas: '' }
  formError.value = ''
  showModal.value = true
}

const openEditModal = (kelas) => {
  modalMode.value = 'edit'
  formKelas.value = { ...kelas }
  formError.value = ''
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
}

const saveKelas = async () => {
  isSaving.value = true
  formError.value = ''
  try {
    if (modalMode.value === 'add') {
      await api.post('/kelas', { nama_kelas: formKelas.value.nama_kelas })
    } else {
      await api.put(`/kelas/${formKelas.value.id_kelas}`, { nama_kelas: formKelas.value.nama_kelas })
    }
    showModal.value = false
    await fetchKelas()
  } catch (err) {
    formError.value = err.response?.data?.message || 'Gagal menyimpan. Coba lagi.'
  } finally {
    isSaving.value = false
  }
}

// --- Delete ---
const confirmDelete = (kelas) => {
  deletingId.value = kelas.id_kelas
  deletingName.value = kelas.nama_kelas
  showDeleteConfirm.value = true
}

const cancelDelete = () => {
  showDeleteConfirm.value = false
  deletingId.value = null
  deletingName.value = ''
}

const doDelete = async () => {
  isDeleting.value = true
  try {
    await api.delete(`/kelas/${deletingId.value}`)
    showDeleteConfirm.value = false
    deletingId.value = null
    await fetchKelas()
  } catch (err) {
    console.error('Gagal hapus kelas:', err)
  } finally {
    isDeleting.value = false
  }
}

onMounted(fetchKelas)
</script>

<template>
  <div class="flex flex-col h-full bg-surface">
    <!-- Header -->
    <header class="mb-10 px-8 pt-8">
      <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
          <h2 class="text-3xl font-extrabold text-on-surface font-headline mb-2 tracking-tight">Manajemen Kelas</h2>
          <p class="text-on-surface-variant text-sm max-w-lg">Kelola data kelas untuk pengelompokan siswa dan filter data absensi.</p>
        </div>
        <button @click="openAddModal" class="px-6 py-2.5 bg-primary text-white shadow-md rounded-full font-bold text-sm transition-all hover:-translate-y-0.5 active:scale-95 flex items-center gap-2">
          <span class="material-symbols-outlined text-[20px]">add_circle</span>
          Tambah Kelas
        </button>
      </div>
    </header>

    <div class="px-8 pb-8 flex-1 flex flex-col">
      <!-- Kelas Table Section -->
      <div class="bg-surface-container-lowest rounded-2xl shadow-sm overflow-hidden flex-1 flex flex-col mb-10 w-full lg:w-2/3">
        <div class="p-6 border-b border-surface-container flex items-center justify-between">
          <h3 class="text-xl font-bold font-headline text-on-surface">
            Daftar Kelas Terdaftar
          </h3>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead class="bg-surface-container-low">
              <tr>
                <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider w-24">No</th>
                <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Nama Kelas</th>
                <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-surface-container">
              <!-- Loading -->
              <tr v-if="isLoading">
                <td colspan="3" class="px-8 py-12 text-center">
                  <span class="material-symbols-outlined text-4xl text-primary animate-spin block mb-2">progress_activity</span>
                  <p class="text-on-surface-variant text-sm">Memuat data kelas...</p>
                </td>
              </tr>
              <!-- Empty -->
              <tr v-else-if="kelasList.length === 0">
                <td colspan="3" class="px-8 py-12 text-center">
                  <span class="material-symbols-outlined text-4xl text-on-surface-variant/40 block mb-2">domain_disabled</span>
                  <p class="text-on-surface-variant text-sm">Tidak ada data kelas.</p>
                </td>
              </tr>
              <!-- Data -->
              <tr v-for="(kelas, idx) in kelasList" :key="kelas.id_kelas" class="hover:bg-surface-container-low/50 transition-colors group">
                <td class="px-6 py-4 text-sm font-medium text-on-surface-variant">{{ idx + 1 }}</td>
                <td class="px-6 py-4">
                  <span class="text-sm font-bold text-on-surface">{{ kelas.nama_kelas }}</span>
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button @click="openEditModal(kelas)" class="p-1.5 text-primary hover:bg-primary/10 rounded-lg transition-all" title="Edit">
                      <span class="material-symbols-outlined text-[20px]">edit</span>
                    </button>
                    <button @click="confirmDelete(kelas)" class="p-1.5 text-error hover:bg-error/10 rounded-lg transition-all" title="Hapus">
                      <span class="material-symbols-outlined text-[20px]">delete</span>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Modal Form (Add/Edit) -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 glass-overlay">
      <div class="bg-surface-container-lowest w-full max-w-sm rounded-[24px] shadow-2xl p-8 relative animate-in fade-in zoom-in duration-300">
        <h2 class="text-2xl font-bold text-on-surface font-headline mb-6">{{ modalMode === 'add' ? 'Tambah Kelas' : 'Edit Kelas' }}</h2>

        <div v-if="formError" class="mb-4 px-4 py-3 bg-error/10 border border-error/20 rounded-xl text-error text-sm font-medium">
          {{ formError }}
        </div>

        <form @submit.prevent="saveKelas" class="space-y-4">
          <div class="flex flex-col gap-2">
            <label class="text-xs font-semibold text-on-surface-variant ml-1 uppercase tracking-wider">Nama Kelas</label>
            <input v-model="formKelas.nama_kelas" required placeholder="Cth: XII RPL 1" class="w-full bg-surface-container-highest border-none focus:ring-2 focus:ring-primary/20 rounded-lg px-4 py-3 text-on-surface font-medium" type="text"/>
          </div>
          <div class="flex justify-end gap-3 pt-6">
            <button @click="closeModal" class="px-6 py-2.5 rounded-full font-semibold text-on-surface-variant hover:bg-surface-container" type="button">Batal</button>
            <button :disabled="isSaving" class="px-6 py-2.5 rounded-full bg-primary text-white font-bold hover:brightness-110 shadow-md active:scale-95 transition-all disabled:opacity-70 flex items-center gap-2" type="submit">
              <span v-if="isSaving" class="material-symbols-outlined animate-spin text-sm">progress_activity</span>
              Simpan
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal Delete Confirm -->
    <div v-if="showDeleteConfirm" class="fixed inset-0 z-50 flex items-center justify-center p-4 glass-overlay">
      <div class="bg-surface-container-lowest w-full max-w-sm rounded-[24px] shadow-2xl p-8 animate-in fade-in zoom-in duration-300">
        <div class="flex flex-col items-center text-center gap-4">
          <div class="w-16 h-16 bg-error/10 rounded-full flex items-center justify-center">
            <span class="material-symbols-outlined text-error text-3xl">delete_forever</span>
          </div>
          <h2 class="text-xl font-bold text-on-surface font-headline">Hapus Kelas?</h2>
          <p class="text-sm text-on-surface-variant">Kelas <strong>{{ deletingName }}</strong> akan dihapus permanen.</p>
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
