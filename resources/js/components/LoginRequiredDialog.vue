<template>
  <Dialog :open="isOpen" @update:open="$emit('update:isOpen', $event)">
    <DialogContent class="sm:max-w-[425px]">
      <DialogHeader>
        <DialogTitle>Login Diperlukan</DialogTitle>
        <DialogDescription>
          Untuk melakukan pemesanan, Anda perlu login terlebih dahulu. Silakan login atau daftar jika belum memiliki akun.
        </DialogDescription>
      </DialogHeader>
      <div class="flex flex-col gap-4 py-4">
        <Button asChild variant="default" class="w-full">
          <Link :href="route('login')">
            Login
          </Link>
        </Button>
        <Button asChild variant="outline" class="w-full">
          <Link :href="route('register')">
            Daftar Akun Baru
          </Link>
        </Button>
      </div>
    </DialogContent>
  </Dialog>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';

defineProps({
  isOpen: {
    type: Boolean,
    required: true
  }
});

defineEmits(['update:isOpen']);
</script> 