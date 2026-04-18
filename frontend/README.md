# E-Solat THP Frontend - Vue.js Application

Aplikasi single-page modern yang responsif dibangun dengan Vue.js 3.5, menyediakan interface intuitif untuk sistem absensi solat sekolah Tahap Hijrah Putrajaya.

## 🏗️ Arsitektur Vue.js

Frontend ini mengikuti best practices Vue.js dan standar JavaScript modern:

- **Composition API**: Reactive programming Vue.js modern
- **Component-Based**: Komponen modular dan reusable
- **Single File Components**: Template, script, dan style terintegrasi
- **Progressive Enhancement**: Arsitektur PWA-ready
- **TypeScript-Ready**: Disiapkan untuk migrasi TypeScript
- **Accessibility**: Pola desain WCAG compliant

## 📁 Struktur Directory Frontend

```
frontend/
├── public/                   # Static Assets
│   ├── favicon.ico          # Site favicon
│   └── index.html           # HTML template
├── src/
│   ├── components/          # Komponen Vue Reusable
│   ├── layouts/             # Page Layouts
│   │   └── DefaultLayout.vue # Layout utama aplikasi
│   ├── views/               # Komponen Halaman
│   │   ├── HomeView.vue     # Halaman utama (landing)
│   │   ├── LoginView.vue    # Halaman login
│   │   ├── RegisterView.vue # Halaman registrasi
│   │   ├── AbsensiView.vue  # Halaman absensi siswa (NIS input)
│   │   └── admin/           # Halaman admin
│   │       ├── DashboardView.vue     # Dashboard admin
│   │       ├── DataSiswaView.vue      # Manajemen data siswa
│   │       ├── TambahSiswaView.vue    # Tambah data siswa
│   │       ├── ImportSiswaView.vue    # Import data siswa
│   │       └── AbsensiSiswaView.vue   # Laporan absensi
│   ├── router/              # Konfigurasi Vue Router
│   │   └── index.js         # Definisi routes
│   ├── services/            # API Services
│   │   ├── api.js           # Konfigurasi Axios
│   │   ├── auth.js          # Service autentikasi
│   │   ├── siswa.js         # Service data siswa
│   │   ├── absensi.js       # Service absensi
│   │   └── kelas.js         # Service data kelas
│   ├── stores/              # State Management
│   │   ├── auth.js          # State autentikasi
│   │   ├── siswa.js         # State data siswa
│   │   └── ui.js            # State UI
│   ├── assets/              # Static Assets
│   │   ├── css/             # Stylesheets
│   │   ├── images/          # Images
│   │   └── icons/           # Icons
│   ├── utils/               # Utility Functions
│   │   ├── dateHelpers.js   # Utility tanggal
│   │   ├── formatters.js    # Data formatters
│   │   └── validators.js    # Form validators
│   ├── composables/         # Vue Composables
│   │   ├── useAuth.js       # Composable autentikasi
│   │   ├── useApi.js        # Composable API
│   │   └── useLocalStorage.js # Composable local storage
│   ├── App.vue              # Root Component
│   ├── main.js              # Entry Point Aplikasi
│   └── style.css            # Global Styles
├── dist/                    # Build Output Directory
├── node_modules/            # Node.js Dependencies
├── package.json             # Project Dependencies & Scripts
├── vite.config.js           # Konfigurasi Vite
├── tailwind.config.js       # Konfigurasi TailwindCSS
└── postcss.config.js        # Konfigurasi PostCSS
```

## 🔧 Core Technologies & Packages

### Vue.js Framework (3.5)

- **Composition API**: Modern reactive programming
- **Reactivity System**: Efficient state management
- **Component Lifecycle**: Optimized component lifecycle hooks
- **Teleport**: Portal-like rendering
- **Suspense**: Async component handling

### Build Tools & Development

- **Vite 8.0**: Fast development server and build tool
- **Vue Router 5.0**: Client-side routing
- **TailwindCSS 3.4**: Utility-first CSS framework
- **PostCSS**: CSS processing and optimization
- **Autoprefixer**: CSS vendor prefixing

### HTTP & Data Handling

- **Axios 1.15**: HTTP client for API requests
- **PapaParse 5.5**: CSV parsing for data imports
- **LocalStorage**: Client-side data persistence

### UI & Styling

- **TailwindCSS**: Utility-first CSS framework
- **Tailwind Forms**: Form styling utilities
- **Tailwind Container Queries**: Responsive container queries
- **Custom CSS**: Application-specific styles

## 🎨 Design System & UI Components

### Design Principles

- **Mobile-First**: Responsive design untuk semua perangkat
- **Accessibility**: WCAG 2.1 AA compliance
- **Performance**: Dioptimalkan untuk loading cepat
- **Consistency**: Bahasa desain yang terpadu
- **Usability**: Pengalaman pengguna yang intuitif

### Color Palette

```css
/* Primary Colors */
--primary-blue: #0050cb; /* Primary blue */
--primary-green: #10b981; /* Success green */
--primary-gold: #f59e0b; /* Warning gold */

/* Neutral Colors */
--gray-50: #f9fafb;
--gray-900: #111827;

/* Semantic Colors */
--success: #10b981;
--warning: #f59e0b;
--error: #ef4444;
--info: #3b82f6;
```

### Typography

- **Font Family**: Plus Jakarta Sans, Inter, system-ui, sans-serif
- **Headings**: Bold, ukuran hierarkis
- **Body Text**: Regular weight, keterbacaan optimal
- **Material Design**: Menggunakan Material Design 3

### Component Library

- **Buttons**: Primary, secondary, outline, ghost variants
- **Forms**: Input, select, textarea, checkbox, radio
- **Cards**: Container konten dengan shadows
- **Modals**: Overlay dialogs untuk interaksi
- **Navigation**: Header, sidebar, breadcrumb components
- **Tables**: Data tables dengan sorting dan pagination
- **Charts**: Visualisasi data absensi

## 🚀 Fitur Aplikasi

### � Sistem Absensi Siswa

- **Input NIS**: Form input NIS yang praktis
- **Validasi Real-time**: Validasi data otomatis
- **Feedback Visual**: Konfirmasi absensi dengan popup
- **Riwayat**: Menampilkan data absensi yang berhasil
- **Error Handling**: Pesan error yang jelas

### �‍💼 Dashboard Admin

- **Statistik Overview**: Total siswa, kehadiran hari ini
- **Manajemen Siswa**: CRUD data siswa lengkap
- **Import Data**: Import massal data siswa (CSV/Excel)
- **Laporan Absensi**: Rekap per siswa dan per kelas
- **Data Visualization**: Grafik kehadiran

### � Autentikasi & Keamanan

- **Login System**: Login untuk admin dan petugas
- **Session Management**: Token-based authentication
- **Role-Based Access**: Hak akses sesuai peran
- **Auto-logout**: Logout otomatis saat timeout

### 📱 User Experience

- **Responsive Design**: Optimal di desktop dan mobile
- **Dark/Light Theme**: Dukungan tema gelap/terang
- **Loading States**: Indikator loading yang jelas
- **Micro-interactions**: Animasi dan transisi halus

### 📈 Fitur Manajemen Data

- **Data Siswa**: NIS, nama, kelas, jenis kelamin
- **Manajemen Kelas**: Tambah/edit/hapus kelas
- **Import/Export**: CSV/Excel import dan export
- **Search & Filter**: Pencarian dan filter data
- **Pagination**: Navigasi data yang efisien

## 🛠️ Development Workflow

### Local Development

```bash
# Install dependencies
npm install

# Start development server
npm run dev

# Build for production
npm run build

# Preview production build
npm run preview
```

### Code Organization

- **Component Naming**: PascalCase for components
- **File Structure**: Feature-based organization
- **Imports**: Absolute imports with @ alias
- **Export Patterns**: Named exports for components

### State Management

- **Local State**: Vue ref/reactive for component state
- **Global State**: Pinia stores for application state
- **Server State**: API calls with caching strategies
- **Persistence**: LocalStorage for user preferences

## 🎯 Routing & Navigation

### Route Structure

```javascript
const routes = [
  { path: "/", component: HomeView },
  { path: "/login", component: LoginView },
  { path: "/register", component: RegisterView },
  { path: "/absensi", component: AbsensiView }, // Public absensi
  {
    path: "/admin",
    component: AdminLayout,
    meta: { requiresAuth: true },
    children: [
      { path: "dashboard", component: DashboardView },
      { path: "siswa", component: DataSiswaView },
      { path: "siswa/tambah", component: TambahSiswaView },
      { path: "siswa/import", component: ImportSiswaView },
      { path: "absensi", component: AbsensiSiswaView },
    ],
  },
];
```

### Navigation Guards

- **Authentication**: Protect authenticated admin routes
- **Authorization**: Role-based access control
- **Redirects**: Automatic redirects for login/logout
- **Meta Information**: Page titles and descriptions

## 📱 Responsive Design

### Breakpoints

```css
/* TailwindCSS Breakpoints */
sm: 640px   /* Small devices */
md: 768px   /* Medium devices */
lg: 1024px  /* Large devices */
xl: 1280px  /* Extra large devices */
2xl: 1536px /* 2X large devices */
```

### Mobile Optimization

- **Touch-Friendly**: Large tap targets untuk input NIS
- **Performance**: Dioptimalkan untuk mobile networks
- **PWA Features**: Offline support untuk absensi
- **Viewport**: Proper viewport meta tags

## 🔌 API Integration

### HTTP Client Configuration

```javascript
// Axios configuration dengan interceptors
axios.defaults.baseURL = process.env.VITE_API_URL || "/api";
axios.defaults.headers.common["Accept"] = "application/json";
axios.defaults.headers.common["Content-Type"] = "application/json";
```

### API Services

- **Authentication Service**: Login, logout, token management
- **Siswa Service**: CRUD data siswa, import CSV
- **Absensi Service**: Input absensi, laporan, rekap
- **Kelas Service**: Manajemen data kelas
- **Petugas Service**: Manajemen data petugas

### Error Handling

- **Global Interceptors**: Centralized error handling
- **User Notifications**: Toast notifications untuk errors
- **Retry Logic**: Automatic retry untuk failed requests
- **Offline Support**: Cached responses untuk offline mode

### Public API Access

- **Absensi Public**: Endpoint `/api/absensi/public` tanpa auth
- **NIS Validation**: Validasi NIS real-time
- **Success Feedback**: Popup konfirmasi absensi berhasil

## 🧪 Testing Strategy

### Unit Testing

- **Component Tests**: Individual component testing
- **Composable Tests**: Composable function testing
- **Utility Tests**: Helper function testing
- **Service Tests**: API service testing

### Integration Testing

- **Navigation Tests**: Route navigation testing
- **API Integration**: End-to-end API testing
- **User Flow Tests**: Complete user journey testing

### Testing Tools

- **Vitest**: Fast unit testing framework
- **Vue Test Utils**: Vue.js testing utilities
- **Testing Library**: User-centric testing approach
- **Mock Service Worker**: API mocking

## 🚀 Performance Optimization

### Bundle Optimization

- **Code Splitting**: Route-based code splitting
- **Tree Shaking**: Remove unused code
- **Asset Optimization**: Image and font optimization
- **Compression**: Gzip compression for assets

### Runtime Performance

- **Lazy Loading**: Components and images loaded on demand
- **Virtual Scrolling**: For large lists (if needed)
- **Memoization**: Computed property optimization
- **Debouncing**: Input and scroll event optimization

### Caching Strategies

- **HTTP Caching**: Proper cache headers
- **Service Worker**: Offline caching
- **Local Storage**: User preference caching
- **Memory Caching**: API response caching

## 🔧 Configuration Files

### Vite Configuration

```javascript
// vite.config.js
export default defineConfig({
  plugins: [vue()],
  resolve: {
    alias: {
      "@": path.resolve(__dirname, "./src"),
    },
  },
  server: {
    port: 3000,
    proxy: {
      "/api": "http://localhost:8000",
    },
  },
});
```

### TailwindCSS Configuration

```javascript
// tailwind.config.js
module.exports = {
  content: ["./index.html", "./src/**/*.{vue,js,ts,jsx,tsx}"],
  theme: {
    extend: {
      colors: {
        primary: {
          green: "#10b981",
          blue: "#3b82f6",
          gold: "#f59e0b",
        },
      },
    },
  },
};
```

## 🌐 Deployment

### Production Build

```bash
# Build for production
npm run build

# Preview production build
npm run preview

# Analyze bundle size
npm run build -- --analyze
```

### Environment Variables

```env
# API Configuration
VITE_API_URL=http://localhost:8000/api
VITE_APP_NAME=E-Solat THP
VITE_APP_VERSION=1.0.0

# Feature Flags
VITE_ENABLE_PWA=true
VITE_ENABLE_NOTIFICATIONS=true
```

### Static Hosting

- **Netlify**: Continuous deployment and hosting
- **Vercel**: Optimized for Vue.js applications
- **GitHub Pages**: Free static hosting
- **AWS S3**: Scalable static asset hosting

## 📚 Best Practices

### Code Quality

- **ESLint**: JavaScript linting and formatting
- **Prettier**: Code formatting
- **TypeScript**: Type safety (optional migration)
- **Husky**: Git hooks for code quality

### Security

- **XSS Prevention**: Proper input sanitization
- **CSRF Protection**: Token-based CSRF protection
- **Content Security Policy**: CSP headers configuration
- **Dependency Updates**: Regular security updates

### Accessibility

- **ARIA Labels**: Screen reader support
- **Keyboard Navigation**: Full keyboard accessibility
- **Color Contrast**: WCAG compliant color ratios
- **Focus Management**: Proper focus handling

## 🤝 Contributing to Frontend

1. Ikuti Vue.js style guide
2. Gunakan Composition API untuk komponen baru
3. Tulis tests untuk fitur baru
4. Update dokumentasi
5. Pastikan accessibility compliance
6. Optimalkan untuk performance
7. Gunakan semantic HTML5 elements

---

**E-Solat THP Frontend** - Aplikasi Vue.js yang modern, accessible, dan performant untuk sistem absensi solat sekolah.
