# E-Solat THP Frontend - Vue.js Application

A modern, responsive single-page application built with Vue.js 3.5, providing an intuitive interface for Islamic prayer times and community services for the Tahap Hijrah Putrajaya community.

## 🏗️ Vue.js Architecture Overview

This frontend follows Vue.js best practices and modern JavaScript standards:

- **Composition API**: Modern Vue.js reactive programming
- **Component-Based**: Modular, reusable components
- **Single File Components**: Co-located template, script, and style
- **Progressive Enhancement**: PWA-ready architecture
- **TypeScript-Ready**: Prepared for TypeScript migration
- **Accessibility**: WCAG compliant design patterns

## 📁 Frontend Directory Structure

```
frontend/
├── public/                   # Static Assets
│   ├── favicon.ico          # Site favicon
│   └── index.html           # HTML template
├── src/
│   ├── components/          # Reusable Vue Components
│   │   ├── common/          # Shared components
│   │   ├── forms/           # Form components
│   │   ├── layout/          # Layout components
│   │   └── ui/              # UI elements
│   ├── layouts/             # Page Layouts
│   │   ├── DefaultLayout.vue # Main layout
│   │   ├── AuthLayout.vue   # Authentication layout
│   │   └── AdminLayout.vue  # Admin layout
│   ├── views/               # Page Components
│   │   ├── Home.vue         # Home page
│   │   ├── PrayerTimes.vue  # Prayer times page
│   │   ├── Calendar.vue     # Islamic calendar
│   │   ├── Community.vue    # Community features
│   │   ├── Profile.vue      # User profile
│   │   ├── Login.vue        # Login page
│   │   └── Register.vue     # Registration page
│   ├── router/              # Vue Router Configuration
│   │   └── index.js         # Route definitions
│   ├── services/            # API Services
│   │   ├── api.js           # Axios configuration
│   │   ├── auth.js          # Authentication service
│   │   ├── prayerTimes.js   # Prayer times API
│   │   └── calendar.js      # Calendar API
│   ├── stores/              # State Management
│   │   ├── auth.js          # Authentication state
│   │   ├── prayerTimes.js   # Prayer times state
│   │   └── ui.js            # UI state
│   ├── assets/              # Static Assets
│   │   ├── css/             # Stylesheets
│   │   ├── images/          # Images
│   │   └── icons/           # Icons
│   ├── utils/               # Utility Functions
│   │   ├── dateHelpers.js   # Date utilities
│   │   ├── formatters.js    # Data formatters
│   │   └── validators.js    # Form validators
│   ├── composables/         # Vue Composables
│   │   ├── useAuth.js       # Authentication composable
│   │   ├── useApi.js        # API composable
│   │   └── useLocalStorage.js # Local storage composable
│   ├── App.vue              # Root Component
│   ├── main.js              # Application Entry Point
│   └── style.css            # Global Styles
├── dist/                    # Build Output Directory
├── node_modules/            # Node.js Dependencies
├── package.json             # Project Dependencies & Scripts
├── vite.config.js           # Vite Configuration
├── tailwind.config.js       # TailwindCSS Configuration
└── postcss.config.js        # PostCSS Configuration
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
- **Mobile-First**: Responsive design for all devices
- **Accessibility**: WCAG 2.1 AA compliance
- **Performance**: Optimized for fast loading
- **Consistency**: Unified design language
- **Usability**: Intuitive user experience

### Color Palette
```css
/* Primary Colors */
--primary-green: #10b981;    /* Islamic green */
--primary-blue: #3b82f6;     /* Sky blue */
--primary-gold: #f59e0b;     /* Gold accent */

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
- **Font Family**: Inter, system-ui, sans-serif
- **Headings**: Bold, hierarchical sizing
- **Body Text**: Regular weight, optimal readability
- **Arabic Text**: Specialized Arabic font support

### Component Library
- **Buttons**: Primary, secondary, outline, ghost variants
- **Forms**: Input, select, textarea, checkbox, radio
- **Cards**: Content containers with shadows
- **Modals**: Overlay dialogs for interactions
- **Navigation**: Header, sidebar, breadcrumb components
- **Tables**: Data tables with sorting and pagination
- **Charts**: Prayer time visualizations (if implemented)

## 🚀 Application Features

### 🕌 Prayer Times Display
- **Daily Schedule**: Today's prayer times with current status
- **Monthly View**: Calendar view of prayer times
- **Location-Based**: Automatic location detection
- **Adhan Notifications**: Prayer time alerts
- **Qibla Direction**: Compass for Qibla direction

### 📅 Islamic Calendar
- **Hijri Calendar**: Islamic date display
- **Event Tracking**: Important Islamic dates
- **Ramadan Schedule**: Special Ramadan timings
- **Holiday Information**: Islamic holidays

### 👥 User Features
- **Authentication**: Login, registration, profile management
- **Personalization**: Customizable preferences
- **Notifications**: Prayer time reminders
- **Settings**: Language, theme, notification preferences

### 🏢 Community Features
- **Announcements**: Community news and updates
- **Events**: Community events and activities
- **Donations**: Online donation system (if implemented)
- **Feedback**: User feedback and suggestions

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
  { path: '/', component: Home },
  { path: '/prayer-times', component: PrayerTimes },
  { path: '/calendar', component: Calendar },
  { path: '/community', component: Community },
  { path: '/profile', component: Profile, meta: { requiresAuth: true } },
  { path: '/login', component: Login },
  { path: '/register', component: Register },
  { path: '/admin', component: AdminLayout, meta: { requiresAuth: true, role: 'admin' } }
];
```

### Navigation Guards
- **Authentication**: Protect authenticated routes
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
- **Touch-Friendly**: Large tap targets and gestures
- **Performance**: Optimized for mobile networks
- **PWA Features**: Offline support and app-like experience
- **Viewport**: Proper viewport meta tags

## 🔌 API Integration

### HTTP Client Configuration
```javascript
// Axios configuration with interceptors
axios.defaults.baseURL = process.env.VITE_API_URL;
axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.headers.common['Content-Type'] = 'application/json';
```

### API Services
- **Authentication Service**: Login, logout, token management
- **Prayer Times Service**: Fetch prayer schedules
- **Calendar Service**: Islamic calendar data
- **Community Service**: Announcements and events
- **Profile Service**: User profile management

### Error Handling
- **Global Interceptors**: Centralized error handling
- **User Notifications**: Toast notifications for errors
- **Retry Logic**: Automatic retry for failed requests
- **Offline Support**: Cached responses for offline mode

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
      '@': path.resolve(__dirname, './src')
    }
  },
  server: {
    port: 3000,
    proxy: {
      '/api': 'http://localhost:8000'
    }
  }
});
```

### TailwindCSS Configuration
```javascript
// tailwind.config.js
module.exports = {
  content: ['./index.html', './src/**/*.{vue,js,ts,jsx,tsx}'],
  theme: {
    extend: {
      colors: {
        primary: {
          green: '#10b981',
          blue: '#3b82f6',
          gold: '#f59e0b'
        }
      }
    }
  }
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

1. Follow Vue.js style guide
2. Use Composition API for new components
3. Write tests for new features
4. Update documentation
5. Ensure accessibility compliance
6. Optimize for performance
7. Use semantic HTML5 elements

---

**E-Solat THP Frontend** - A modern, accessible, and performant Vue.js application for Islamic prayer services.
