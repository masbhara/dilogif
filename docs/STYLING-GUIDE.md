# Panduan Styling Dilogif App

Dokumen ini merupakan panduan mengenai sistem styling aplikasi Dilogif, yang dibuat untuk memastikan konsistensi dan kemudahan pengelolaan kode CSS.

## Pendekatan Styling

Aplikasi ini menggunakan pendekatan **Theme-First Tailwind CSS** yang mengutamakan variabel tema terpusat dengan implementasi melalui Tailwind CSS.

### Prinsip Utama Styling

1. **Satu Sumber Kebenaran**: Semua nilai styling dasar (warna, font, dll) didefinisikan di `app.css` sebagai CSS Variables
2. **Tailwind Sebagai Interface**: Variabel CSS diekspos sebagai tema Tailwind melalui `tailwind.config.js`
3. **Layer System**: Style dipisahkan melalui sistem layer (`@layer`) untuk menghindari konflik spesifisitas

## Struktur File Styling

### 1. Konfigurasi Tema: CSS Variables (`app.css`)

File `app.css` berisi deklarasi semua variabel CSS yang menjadi fondasi sistem tema:

```css
:root {
  /* Base Theme */
  --font-family-base: 'Manrope', ui-sans-serif, system-ui, sans-serif;
  --border-color-base: #e2e8f0;
  --radius-base: 0.375rem;
  --transition-base: 150ms;
  
  /* Sidebar Component */
  --sidebar-accent: theme('colors.primary.500');
  --sidebar-hover: theme('colors.slate.700 / 40%');
  /* ... etc */
}

.dark {
  /* Dark Theme Overrides */
  --border-color-base: #334155;
  /* ... etc */
}
```

### 2. Integrasi dengan Tailwind (`tailwind.config.js`)

Variabel CSS diekspos sebagai tema Tailwind untuk akses melalui kelas-kelas utility:

```js
theme: {
  extend: {
    colors: {
      sidebar: {
        accent: {
          DEFAULT: 'var(--sidebar-accent)',
          foreground: 'var(--sidebar-accent-foreground)',
        },
        /* ... etc */
      },
    },
  },
},
```

### 3. Layer-based Components (`app.css`)

Komponen khusus yang tidak bisa dibuat hanya dengan utility diletakkan dalam `@layer components`:

```css
@layer components {
  .sidebar-menu-button {
    @apply flex w-full items-center gap-2 rounded-md p-2;
    @apply text-sm text-slate-700 dark:text-slate-300;
    @apply hover:bg-gray-100 dark:hover:bg-slate-700/40;
    /* ... etc */
  }
}
```

## Cara Menggunakan Sistem Styling

### 1. Menggunakan Warna Tema

Gunakan kelas warna dari tema Tailwind, bukan hardcoded values:

**❌ TIDAK DISARANKAN**:
```html
<div class="bg-[#0ea5e9] text-white">...</div>
```

**✅ DISARANKAN**:
```html
<div class="bg-primary-500 text-white">...</div>
```

### 2. Menggunakan Variabel Komponen

Untuk styling komponen yang lebih kompleks, gunakan tema komponen yang sudah didefinisikan:

**❌ TIDAK DISARANKAN**:
```html
<div class="bg-primary-600 hover:bg-primary-700 text-white">...</div>
```

**✅ DISARANKAN**:
```html
<div class="bg-sidebar-accent text-sidebar-accent-foreground">...</div>
```

### 3. Dark Mode Support

Semua komponen sudah support dark mode melalui kelas Tailwind:

```html
<div class="bg-white dark:bg-slate-800 text-gray-900 dark:text-gray-100">...</div>
```

## Pola Styling Umum

### Tabel

```html
<table class="w-full border-collapse">
  <thead class="bg-slate-100 dark:bg-slate-700">
    <tr>
      <th class="px-4 py-2 text-left text-sm font-semibold">...</th>
    </tr>
  </thead>
  <tbody class="bg-white dark:bg-slate-800">
    <tr class="border-b border-slate-200 dark:border-slate-700">...</tr>
  </tbody>
</table>
```

### Card / Container

```html
<div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
  ...
</div>
```

### Button

```html
<button class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md">...</button>

<!-- Atau gunakan komponen Button.vue yang sudah ada -->
<Button variant="primary">Button Text</Button>
```

## Cara Mengubah Tema

Untuk mengubah tema secara global:

1. Ubah variabel CSS di `app.css`
2. Tailwind akan menggunakan nilai-nilai tersebut secara otomatis

Contoh mengubah warna primary:

```css
:root {
  --sidebar-accent: theme('colors.blue.500'); /* Mengubah dari nilai default */
}
```

## Praktik Terbaik

1. **Hindari Inline Styles**: Gunakan kelas Tailwind atau komponen yang sudah ada
2. **Gunakan Design System**: Selalu rujuk dokumentasi ini sebelum membuat style baru
3. **Konsistensi Penamaan**: Ikuti konvensi penamaan yang sudah ada
4. **Gunakan Komponen**: Untuk UI yang kompleks, buat dan gunakan komponen Vue
5. **Minimize Custom CSS**: Gunakan utility Tailwind sebisa mungkin sebelum menulis CSS kustom

---

Dokumen ini akan terus diperbarui seiring perkembangan aplikasi. Jika ada pertanyaan atau saran, silakan ajukan di Issue GitHub. 