import { ref } from 'vue'

export const cartCount = ref(0)

export function updateCartCount(count) {
  cartCount.value = count
}

// Initialize cart count from page props if available
export function initializeCartCount(initialCount) {
  cartCount.value = initialCount
} 