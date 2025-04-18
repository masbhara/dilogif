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
    bg: 'bg-secondary-600',
    hover: 'hover:bg-secondary-700',
    text: 'text-white',
    border: 'border-secondary-600',
    focusRing: 'focus:ring-secondary-500',
    disabled: 'disabled:bg-secondary-400 disabled:text-white disabled:cursor-not-allowed',
    loading: 'bg-secondary-500',
    spinner: 'border-white border-t-transparent'
  },
  
  // Outline style - transparan dengan border
  outline: {
    bg: 'bg-transparent',
    hover: 'hover:bg-primary-50 hover:text-primary-700',
    text: 'text-primary-600',
    border: 'border border-primary-300',
    focusRing: 'focus:ring-primary-300',
    disabled: 'disabled:bg-transparent disabled:text-primary-300 disabled:cursor-not-allowed disabled:border-primary-200',
    loading: 'bg-primary-50',
    spinner: 'border-primary-500 border-t-transparent'
  },
  
  // Danger/destructive untuk actions berbahaya
  danger: {
    bg: 'bg-danger-600',
    hover: 'hover:bg-danger-700',
    text: 'text-white',
    border: 'border-danger-600',
    focusRing: 'focus:ring-danger-500',
    disabled: 'disabled:bg-danger-400 disabled:cursor-not-allowed',
    loading: 'bg-danger-500',
    spinner: 'border-white border-t-transparent'
  },
  
  // Success untuk aksi berhasil/positif
  success: {
    bg: 'bg-success-600',
    hover: 'hover:bg-success-700', 
    text: 'text-white',
    border: 'border-success-600',
    focusRing: 'focus:ring-success-500',
    disabled: 'disabled:bg-success-400 disabled:cursor-not-allowed',
    loading: 'bg-success-500',
    spinner: 'border-white border-t-transparent'
  },
  
  // Warning untuk peringatan
  warning: {
    bg: 'bg-warning-500',
    hover: 'hover:bg-warning-600',
    text: 'text-white',
    border: 'border-warning-500',
    focusRing: 'focus:ring-warning-400',
    disabled: 'disabled:bg-warning-300 disabled:cursor-not-allowed',
    loading: 'bg-warning-400',
    spinner: 'border-white border-t-transparent'
  },
  
  // Ghost (transparan tanpa border)
  ghost: {
    bg: 'bg-transparent',
    hover: 'hover:bg-primary-100',
    text: 'text-primary-600',
    border: 'border-transparent',
    focusRing: 'focus:ring-primary-200',
    disabled: 'disabled:bg-transparent disabled:text-primary-300 disabled:cursor-not-allowed',
    loading: 'bg-primary-50',
    spinner: 'border-primary-400 border-t-transparent'
  },
  
  // Link style
  link: {
    bg: 'bg-transparent',
    hover: 'hover:underline',
    text: 'text-primary-600',
    border: 'border-transparent',
    focusRing: 'focus:ring-primary-200',
    disabled: 'disabled:text-primary-300 disabled:cursor-not-allowed disabled:no-underline',
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