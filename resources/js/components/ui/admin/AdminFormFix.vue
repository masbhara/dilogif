<template>
  <div>
    <slot />
  </div>
</template>

<script setup>
// Script kosong karena hanya berisi style global
</script>

<style>
/* Custom admin form styling - menggunakan CSS variables */
.custom-select-trigger:focus,
.custom-select-trigger:focus-visible,
.custom-select-trigger:active,
.custom-select-trigger:hover,
.custom-select-trigger:-moz-focusring {
  outline: none !important;
  box-shadow: none !important;
  border-color: var(--color-primary-500) !important;
}

/* Style untuk input admin yang lain */
div:focus,
div:focus-visible,
div:focus-within,
div:active {
  outline: none !important;
  outline-color: transparent !important;
  box-shadow: none !important;
  border-color: var(--color-primary-500) !important;
}

/* Fix untuk Firefox */
div:-moz-focusring,
.custom-select-trigger:-moz-focusring {
  outline: none !important;
  outline-color: transparent !important;
}

/* Fix untuk Safari dan Chrome */
.custom-select-trigger::-webkit-focus-inner {
  border: 0 !important;
}

/* Fix tambahan untuk Chrome */
*:focus {
  outline-color: transparent !important;
}

/* Dark mode adjustment */
.dark .custom-select-trigger:focus,
.dark .custom-select-trigger:active,
.dark div:focus,
.dark div:active {
  border-color: var(--color-primary-400) !important;
}
</style> 