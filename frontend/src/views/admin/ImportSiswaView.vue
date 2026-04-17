<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/services/api'
import Papa from 'papaparse'

const router = useRouter()

// --- State ---
const file = ref(null)
const fileName = ref('')
const parsedData = ref([])
const parseErrors = ref([])
const importResults = ref([])
const isImporting = ref(false)
const importDone = ref(false)
const isDragOver = ref(false)
const isDragOverInput = ref(false)

// --- Computed ---
const validRows = computed(() => parsedData.value.filter(r => r._valid))
const invalidRows = computed(() => parsedData.value.filter(r => !r._valid))
const successCount = computed(() => importResults.value.filter(r => r.status === 'success').length)
const errorCount = computed(() => importResults.value.filter(r => r.status !== 'success').length)

// Format yang diharapkan
const requiredColumns = ['nis', 'nama_siswa', 'jenis_kelamin', 'nama_kelas']

// --- Parse CSV/Excel ---
const handleFileChange = (e) => {
  const f = e.target.files[0]
  if (!f) return
  processFile(f)
}

const handleDrop = (e) => {
  isDragOver.value = false
  const f = e.dataTransfer.files[0]
  if (!f) return
  processFile(f)
}

const processFile = (f) => {
  file.value = f
  fileName.value = f.name
  parsedData.value = []
  parseErrors.value = []
  importResults.value = []
  importDone.value = false

  Papa.parse(f, {
    header: true,
    skipEmptyLines: true,
    complete: (results) => {
      if (results.errors.length > 0) {
        parseErrors.value = results.errors.map(e => e.message)
      }

      const rows = results.data
      if (rows.length === 0) {
        parseErrors.value.push('File tidak memiliki data.')
        return
      }

      // Validasi kolom
      const headers = Object.keys(rows[0]).map(h => h.trim().toLowerCase())
      const missingCols = requiredColumns.filter(c => !headers.includes(c))
      if (missingCols.length > 0) {
        parseErrors.value.push(`Kolom tidak ditemukan: ${missingCols.join(', ')}. Pastikan kolom yang diperlukan ada.`)
        return
      }

      // Validasi per baris
      parsedData.value = rows.map((row, idx) => {
        const normalized = {}
        requiredColumns.forEach(col => {
          normalized[col] = (row[col] || '').trim()
        })

        const errors = []
        if (!normalized.nis) errors.push('NIS kosong')
        if (!normalized.nama_siswa) errors.push('Nama siswa kosong')
        if (!['Laki-laki', 'Perempuan'].includes(normalized.jenis_kelamin)) {
          errors.push('Jenis kelamin harus "Laki-laki" atau "Perempuan"')
        }
        if (!normalized.nama_kelas) errors.push('Nama kelas kosong')

        return {
          ...normalized,
          _row: idx + 2,       // baris di file (header = baris 1)
          _valid: errors.length === 0,
          _errors: errors,
        }
      })
    },
    error: (err) => {
      parseErrors.value.push(err.message)
    }
  })
}

// --- Import ke API ---
const startImport = async () => {
  if (validRows.value.length === 0) return
  isImporting.value = true
  importResults.value = []

  try {
    const payload = validRows.value.map(r => ({
      nis: r.nis,
      nama_siswa: r.nama_siswa,
      jenis_kelamin: r.jenis_kelamin,
      nama_kelas: r.nama_kelas,
    }))

    const res = await api.post('/siswa/import', { data: payload })
    importResults.value = res.data.results || []
    importDone.value = true
  } catch (err) {
    console.error('Import gagal:', err)
    parseErrors.value.push('Gagal mengirim data ke server. Pastikan server berjalan.')
  } finally {
    isImporting.value = false
  }
}

const resetUpload = () => {
  file.value = null
  fileName.value = ''
  parsedData.value = []
  parseErrors.value = []
  importResults.value = []
  importDone.value = false
}

const downloadTemplate = () => {
  const template = 'nis,nama_siswa,jenis_kelamin,nama_kelas\n001,Ahmad Fauzan,Laki-laki,XII TJKT 1\n002,Siti Aminah,Perempuan,XII DKV 1'
  const blob = new Blob([template], { type: 'text/csv;charset=utf-8;' })
  const url = URL.createObjectURL(blob)
  const link = document.createElement('a')
  link.href = url
  link.download = 'template_import_siswa.csv'
  link.click()
  URL.revokeObjectURL(url)
}
</script>

<template>
<div class="p-8">
  <!-- Header -->
  <header class="flex justify-between items-center mb-10">
    <div>
      <h1 class="text-3xl font-extrabold tracking-tight font-headline text-on-surface">Import Data Siswa</h1>
      <p class="text-on-surface-variant mt-1">Upload file CSV atau Excel untuk menambahkan data siswa secara massal.</p>
    </div>
    <div class="flex gap-3">
      <button @click="downloadTemplate" class="flex items-center gap-2 px-4 py-2 bg-surface-container-low rounded-full text-sm font-semibold text-primary hover:bg-surface-container-high transition-all">
        <span class="material-symbols-outlined text-lg">download</span>
        Download Template
      </button>
      <button @click="router.push('/admin/data-siswa')" class="flex items-center gap-2 px-4 py-2 bg-surface-container-low rounded-full text-sm font-semibold text-on-surface-variant hover:bg-surface-container-high transition-all">
        <span class="material-symbols-outlined text-lg">arrow_back</span>
        Kembali
      </button>
    </div>
  </header>

  <div class="grid grid-cols-12 gap-6">
    <!-- Upload Zone -->
    <div class="col-span-12 lg:col-span-8 space-y-6">

      <!-- Format info -->
      <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">
        <div class="flex items-start gap-3">
          <span class="material-symbols-outlined text-blue-600 text-xl mt-0.5">info</span>
          <div>
            <p class="font-semibold text-blue-800 text-sm mb-2">Format Kolom yang Diperlukan:</p>
            <div class="flex flex-wrap gap-2">
              <code v-for="col in requiredColumns" :key="col" class="px-2.5 py-1 bg-blue-100 text-blue-700 rounded-md text-xs font-mono font-bold">{{ col }}</code>
            </div>
            <p class="text-xs text-blue-600 mt-2">Nilai <code class="bg-blue-100 px-1 rounded">jenis_kelamin</code> harus persis: <code class="bg-blue-100 px-1 rounded">Laki-laki</code> atau <code class="bg-blue-100 px-1 rounded">Perempuan</code></p>
          </div>
        </div>
      </div>

      <!-- Drop Zone -->
      <div
        v-if="!parsedData.length && !parseErrors.length"
        @dragover.prevent="isDragOver = true"
        @dragleave="isDragOver = false"
        @drop.prevent="handleDrop"
        :class="isDragOver ? 'border-primary bg-primary/5 scale-[1.01]' : 'border-outline-variant/40 bg-surface-container-lowest'"
        class="border-2 border-dashed rounded-2xl p-12 text-center transition-all cursor-pointer"
        @click="$refs.fileInput.click()"
      >
        <input ref="fileInput" class="hidden" type="file" accept=".csv,.xlsx,.xls" @change="handleFileChange"/>
        <span class="material-symbols-outlined text-5xl text-on-surface-variant/40 mb-4 block">upload_file</span>
        <p class="text-lg font-bold text-on-surface mb-2">Drop file di sini atau klik untuk upload</p>
        <p class="text-sm text-on-surface-variant">Mendukung: CSV, Excel (.xlsx, .xls)</p>
        <p class="text-xs text-on-surface-variant/60 mt-2">Pastikan menggunakan format kolom yang sesuai</p>
      </div>

      <!-- Parse Errors -->
      <div v-if="parseErrors.length > 0" class="bg-error/5 border border-error/20 rounded-xl p-6">
        <h3 class="font-bold text-error mb-3 flex items-center gap-2">
          <span class="material-symbols-outlined">error</span>
          Error saat parsing file:
        </h3>
        <ul class="space-y-1">
          <li v-for="(err, i) in parseErrors" :key="i" class="text-sm text-error">• {{ err }}</li>
        </ul>
        <button @click="resetUpload" class="mt-4 px-4 py-2 bg-error text-white rounded-full text-sm font-semibold">Coba Lagi</button>
      </div>

      <!-- Preview Table -->
      <div v-if="parsedData.length > 0 && !importDone" class="bg-surface-container-lowest rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-surface-container flex justify-between items-center">
          <div>
            <h3 class="font-bold text-on-surface">Preview Data ({{ parsedData.length }} baris)</h3>
            <p class="text-xs text-on-surface-variant mt-0.5">
              <span class="text-emerald-600 font-semibold">{{ validRows.length }} valid</span>
              · <span class="text-error font-semibold">{{ invalidRows.length }} error</span>
            </p>
          </div>
          <div class="flex gap-2">
            <button @click="resetUpload" class="px-4 py-2 rounded-full text-sm font-semibold text-on-surface-variant hover:bg-surface-container">
              Reset
            </button>
            <button
              @click="startImport"
              :disabled="isImporting || validRows.length === 0"
              class="px-6 py-2 rounded-full text-sm font-bold bg-primary text-white hover:brightness-110 active:scale-95 transition-all disabled:opacity-60 flex items-center gap-2"
            >
              <span v-if="isImporting" class="material-symbols-outlined animate-spin text-sm">progress_activity</span>
              <span>{{ isImporting ? 'Mengimport...' : `Import ${validRows.length} Siswa` }}</span>
            </button>
          </div>
        </div>
        <div class="overflow-x-auto max-h-96">
          <table class="w-full text-left border-collapse text-sm">
            <thead class="bg-surface-container-low sticky top-0">
              <tr>
                <th class="px-4 py-3 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Baris</th>
                <th class="px-4 py-3 text-xs font-bold text-on-surface-variant uppercase tracking-wider">NIS</th>
                <th class="px-4 py-3 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Nama Siswa</th>
                <th class="px-4 py-3 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Jenis Kelamin</th>
                <th class="px-4 py-3 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Kelas</th>
                <th class="px-4 py-3 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Status</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-surface-container/30">
              <tr v-for="row in parsedData" :key="row._row" :class="row._valid ? '' : 'bg-error/5'">
                <td class="px-4 py-3 text-on-surface-variant font-mono text-xs">{{ row._row }}</td>
                <td class="px-4 py-3 font-semibold text-primary">{{ row.nis }}</td>
                <td class="px-4 py-3 text-on-surface">{{ row.nama_siswa }}</td>
                <td class="px-4 py-3 text-on-surface-variant">{{ row.jenis_kelamin }}</td>
                <td class="px-4 py-3 text-on-surface-variant">{{ row.nama_kelas }}</td>
                <td class="px-4 py-3">
                  <span v-if="row._valid" class="px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded-full text-xs font-bold">Valid</span>
                  <div v-else>
                    <span class="px-2 py-0.5 bg-error/10 text-error rounded-full text-xs font-bold">Error</span>
                    <p class="text-[10px] text-error mt-0.5">{{ row._errors.join(', ') }}</p>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Import Results -->
      <div v-if="importDone" class="bg-surface-container-lowest rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-surface-container flex justify-between items-center">
          <div>
            <h3 class="font-bold text-on-surface">Hasil Import</h3>
            <p class="text-xs text-on-surface-variant mt-0.5">
              <span class="text-emerald-600 font-semibold">{{ successCount }} berhasil</span>
              · <span class="text-orange-500 font-semibold">{{ errorCount }} gagal/dilewati</span>
            </p>
          </div>
          <div class="flex gap-2">
            <button @click="resetUpload" class="px-4 py-2 rounded-full text-sm font-semibold text-on-surface-variant hover:bg-surface-container">
              Import Lagi
            </button>
            <button @click="router.push('/admin/data-siswa')" class="px-6 py-2 rounded-full text-sm font-bold bg-primary text-white hover:brightness-110">
              Lihat Data Siswa
            </button>
          </div>
        </div>
        <div class="overflow-x-auto max-h-96">
          <table class="w-full text-left border-collapse text-sm">
            <thead class="bg-surface-container-low sticky top-0">
              <tr>
                <th class="px-4 py-3 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Baris</th>
                <th class="px-4 py-3 text-xs font-bold text-on-surface-variant uppercase tracking-wider">NIS</th>
                <th class="px-4 py-3 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Status</th>
                <th class="px-4 py-3 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Keterangan</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-surface-container/30">
              <tr v-for="result in importResults" :key="result.baris">
                <td class="px-4 py-3 text-on-surface-variant font-mono text-xs">{{ result.baris }}</td>
                <td class="px-4 py-3 font-semibold text-primary">{{ result.nis }}</td>
                <td class="px-4 py-3">
                  <span
                    :class="{
                      'bg-emerald-50 text-emerald-700': result.status === 'success',
                      'bg-orange-50 text-orange-700': result.status === 'skip',
                      'bg-error/10 text-error': result.status === 'error',
                    }"
                    class="px-2 py-0.5 rounded-full text-xs font-bold"
                  >
                    {{ result.status === 'success' ? 'Berhasil' : result.status === 'skip' ? 'Dilewati' : 'Gagal' }}
                  </span>
                </td>
                <td class="px-4 py-3 text-xs text-on-surface-variant">{{ result.pesan }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Info Sidebar -->
    <div class="col-span-12 lg:col-span-4 space-y-6">
      <div class="bg-primary bg-gradient-to-br from-primary to-primary-container p-6 rounded-xl text-on-primary shadow-xl">
        <h3 class="text-lg font-bold mb-3 flex items-center gap-2">
          <span class="material-symbols-outlined">help</span>
          Panduan Import
        </h3>
        <ol class="text-sm space-y-3 opacity-90">
          <li class="flex gap-2">
            <span class="font-bold">1.</span> Download template CSV terlebih dahulu.
          </li>
          <li class="flex gap-2">
            <span class="font-bold">2.</span> Isi data siswa sesuai format kolom yang ditentukan.
          </li>
          <li class="flex gap-2">
            <span class="font-bold">3.</span> Simpan dalam format CSV (UTF-8).
          </li>
          <li class="flex gap-2">
            <span class="font-bold">4.</span> Upload file dan periksa preview sebelum import.
          </li>
          <li class="flex gap-2">
            <span class="font-bold">5.</span> Klik Import untuk mulai proses.
          </li>
        </ol>
      </div>

      <div class="bg-surface-container-lowest border border-outline-variant/20 p-6 rounded-xl">
        <h3 class="font-bold text-on-surface mb-3 flex items-center gap-2">
          <span class="material-symbols-outlined text-primary">table_chart</span>
          Contoh Format CSV
        </h3>
        <div class="bg-surface-container rounded-lg p-3 font-mono text-xs text-on-surface-variant overflow-x-auto">
          <div class="font-bold text-primary mb-1">nis,nama_siswa,jenis_kelamin,nama_kelas</div>
          <div>001,Ahmad Fauzan,Laki-laki,XII TJKT 1</div>
          <div>002,Siti Aminah,Perempuan,XII DKV 1</div>
          <div>003,Budi Santoso,Laki-laki,XI RPL 2</div>
        </div>
        <button @click="downloadTemplate" class="mt-4 w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-primary/10 text-primary rounded-full text-sm font-semibold hover:bg-primary/20 transition-all">
          <span class="material-symbols-outlined text-sm">download</span>
          Download Template CSV
        </button>
      </div>
    </div>
  </div>
</div>
</template>
