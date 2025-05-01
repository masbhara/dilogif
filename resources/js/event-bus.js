import { ref } from 'vue'

export const cartCount = ref(0)

export function initializeCartCount(count) {
  cartCount.value = count
}

export function updateCartCount(count) {
  cartCount.value = count
} 