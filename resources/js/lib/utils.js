/**
 * Menggabungkan beberapa class names menjadi satu string
 * Ini adalah utility function yang dibutuhkan oleh komponen Shadcn UI
 * @param  {...any} classes - list class name yang akan digabungkan
 * @returns {string} - class name yang sudah digabungkan
 */
export function cn(...classes) {
  return classes.filter(Boolean).join(" ");
} 