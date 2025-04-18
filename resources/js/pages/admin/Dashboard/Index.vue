<template>
  <Head title="Admin Dashboard" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex flex-col gap-4 p-4 md:p-6 pb-12">
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-secondary-900 dark:text-white">{{ title }}</h1>
      </div>

      <!-- Statistik -->
      <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <Card v-for="(stat, i) in stats" :key="i" class="border border-secondary-200 dark:border-secondary-800 bg-white dark:bg-secondary-900">
          <CardHeader class="flex flex-row items-center justify-between pb-2 space-y-0">
            <CardTitle class="text-sm font-medium text-secondary-900 dark:text-white">{{ stat.title }}</CardTitle>
            <component :is="iconMapping[stat.icon]" class="h-4 w-4 text-secondary-500 dark:text-secondary-400" />
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold text-secondary-900 dark:text-white">{{ stat.value }}</div>
            <p class="text-xs text-secondary-500 dark:text-secondary-400 flex items-center mt-1">
              <TrendingUp v-if="stat.trend === 'up'" class="h-3.5 w-3.5 mr-1 text-success-500 dark:text-success-400" />
              <span v-if="stat.trend === 'up'" class="text-success-500 dark:text-success-400">{{ stat.change }}</span>
              <span v-else class="text-secondary-500 dark:text-secondary-400">{{ stat.change }}</span>
              <span class="ml-1">dari bulan lalu</span>
            </p>
          </CardContent>
        </Card>
      </div>

      <!-- Aktivitas Terbaru -->
      <Card class="border border-secondary-200 dark:border-secondary-800 bg-white dark:bg-secondary-900">
        <CardHeader>
          <CardTitle class="text-secondary-900 dark:text-white">Aktivitas Terbaru</CardTitle>
        </CardHeader>
        <CardContent>
          <div class="space-y-5">
            <div v-for="(activity, i) in activities" :key="i" class="flex items-start gap-4 pb-4 border-b border-secondary-200 dark:border-secondary-800 last:border-0 last:pb-0">
              <div class="flex h-9 w-9 items-center justify-center rounded-full border bg-secondary-100 dark:bg-secondary-800 border-secondary-200 dark:border-secondary-700 text-secondary-900 dark:text-white">
                <span class="text-xs font-bold">{{ activity.user.charAt(0) }}</span>
              </div>
              <div>
                <p class="text-sm text-secondary-900 dark:text-white">
                  <span class="font-medium">{{ activity.user }}</span>
                  {{ activity.action }}
                </p>
                <p class="text-xs text-secondary-500 dark:text-secondary-400 mt-1">{{ activity.time }}</p>
              </div>
            </div>
          </div>
        </CardContent>
        <CardFooter>
          <button class="text-sm text-primary-600 dark:text-primary-400 hover:underline">Lihat semua aktivitas</button>
        </CardFooter>
      </Card>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle, CardFooter } from '@/components/ui/card';
import { BarChart3, Users, Shield, Key, TrendingUp } from 'lucide-vue-next';

// Definisikan tipe
interface StatItem {
  title: string;
  value: string | number;
  icon: string;
  change: string;
  trend: 'up' | 'down' | 'neutral';
}

interface ActivityItem {
  user: string;
  action: string;
  time: string;
}

interface DashboardProps {
  stats: StatItem[];
  activities: ActivityItem[];
  title: string;
}

// Definisikan props dengan tipe
const props = defineProps<DashboardProps>();

// Breadcrumbs untuk navigasi
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Admin',
        href: route('admin.dashboard'),
    },
];

// Mapping nama ikon ke komponen ikon 
const iconMapping: Record<string, any> = {
  Users,
  Shield,
  Key,
  BarChart3
};
</script> 