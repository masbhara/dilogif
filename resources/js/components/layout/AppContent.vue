<script setup lang="ts">
import { SidebarInset } from '@/components/ui/sidebar';
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AppFooter from '@/components/layout/AppFooter.vue';

interface Props {
    variant?: 'header' | 'sidebar';
    class?: string;
    showFooter?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    showFooter: true
});

const className = computed(() => props.class);
</script>

<template>
    <SidebarInset v-if="props.variant === 'sidebar'" :class="className" class="flex flex-col bg-white dark:bg-slate-800" style="background-color: var(--background); color: var(--foreground);">
        <div class="flex flex-col flex-1 min-h-[calc(100vh-4rem)]">
            <slot />
        </div>
        <AppFooter v-if="showFooter" />
    </SidebarInset>
    <main v-else class="mx-auto flex h-full w-full max-w-7xl flex-1 flex-col gap-4 rounded-xl bg-white dark:bg-slate-800" :class="className" style="background-color: var(--background); color: var(--foreground);">
        <div class="flex flex-col flex-1">
            <slot />
        </div>
        <AppFooter v-if="showFooter" />
    </main>
</template>
