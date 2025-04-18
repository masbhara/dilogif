/**
 * File konfigurasi tema untuk tombol
 * Menggunakan variabel ini akan membuat konsistensi warna dan memudahkan perubahan tema di satu tempat
 */

export const buttonTheme = {
  // Warna utama (primary)
  primary: {
    bg: 'bg-primary-600',
    hover: 'hover:bg-primary-700',
    text: 'text-white',
    border: 'border-primary-600',
    focusRing: 'focus:ring-primary-500',
    disabled: 'disabled:bg-primary-400 disabled:cursor-not-allowed',
    loading: 'bg-primary-500',
    spinner: 'border-white border-t-transparent'
  },
  
  // Warna sekunder
  secondary: {
    bg: 'bg-gray-200',
    hover: 'hover:bg-gray-300',
    text: 'text-gray-800',
    border: 'border-gray-300',
    focusRing: 'focus:ring-gray-300',
    disabled: 'disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed',
    loading: 'bg-gray-100',
    spinner: 'border-gray-500 border-t-transparent'
  },
  
  // Outline style - transparan dengan border
  outline: {
    bg: 'bg-transparent',
    hover: 'hover:bg-gray-50 hover:text-gray-900',
    text: 'text-gray-700',
    border: 'border border-gray-300',
    focusRing: 'focus:ring-gray-300',
    disabled: 'disabled:bg-gray-50 disabled:text-gray-400 disabled:cursor-not-allowed disabled:border-gray-200',
    loading: 'bg-gray-50',
    spinner: 'border-gray-500 border-t-transparent'
  },
  
  // Danger/destructive untuk actions berbahaya
  danger: {
    bg: 'bg-red-600',
    hover: 'hover:bg-red-700',
    text: 'text-white',
    border: 'border-red-600',
    focusRing: 'focus:ring-red-500',
    disabled: 'disabled:bg-red-400 disabled:cursor-not-allowed',
    loading: 'bg-red-500',
    spinner: 'border-white border-t-transparent'
  },
  
  // Success untuk aksi berhasil/positif
  success: {
    bg: 'bg-green-600',
    hover: 'hover:bg-green-700', 
    text: 'text-white',
    border: 'border-green-600',
    focusRing: 'focus:ring-green-500',
    disabled: 'disabled:bg-green-400 disabled:cursor-not-allowed',
    loading: 'bg-green-500',
    spinner: 'border-white border-t-transparent'
  },
  
  // Warning untuk peringatan
  warning: {
    bg: 'bg-yellow-500',
    hover: 'hover:bg-yellow-600',
    text: 'text-white',
    border: 'border-yellow-500',
    focusRing: 'focus:ring-yellow-400',
    disabled: 'disabled:bg-yellow-300 disabled:cursor-not-allowed',
    loading: 'bg-yellow-400',
    spinner: 'border-white border-t-transparent'
  },
  
  // Ghost (transparan tanpa border)
  ghost: {
    bg: 'bg-transparent',
    hover: 'hover:bg-gray-100',
    text: 'text-gray-700',
    border: 'border-transparent',
    focusRing: 'focus:ring-gray-200',
    disabled: 'disabled:bg-transparent disabled:text-gray-300 disabled:cursor-not-allowed',
    loading: 'bg-gray-50',
    spinner: 'border-gray-400 border-t-transparent'
  },
  
  // Link style
  link: {
    bg: 'bg-transparent',
    hover: 'hover:underline',
    text: 'text-primary-600',
    border: 'border-transparent',
    focusRing: 'focus:ring-primary-200',
    disabled: 'disabled:text-gray-300 disabled:cursor-not-allowed disabled:no-underline',
    loading: 'bg-transparent',
    spinner: 'border-primary-600 border-t-transparent'
  }
};

// Helper untuk menggabungkan kelas berdasarkan variant
export function getButtonClasses(variant: keyof typeof buttonTheme = 'primary') {
  const theme = buttonTheme[variant];
  
  return `${theme.bg} ${theme.hover} ${theme.text} ${theme.focusRing} ${theme.disabled}`;
}

// Helper untuk mendapatkan warna spinner
export function getSpinnerClasses(variant: keyof typeof buttonTheme = 'primary') {
  return buttonTheme[variant].spinner;
}

// Export default primary variant
export default buttonTheme.primary; 