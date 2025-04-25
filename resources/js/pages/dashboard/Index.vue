<script setup lang="ts">
import { defineComponent as _defineComponent } from "vue";
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle, CardDescription, CardFooter } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { 
    ShoppingBag, Package, Truck, CheckCircle, 
    User, Phone, Mail, MapPin, Clock, ChevronRight 
} from 'lucide-vue-next';

interface OrderSummary {
    pending: number;
    processing: number;
    shipping: number;
    completed: number;
}

interface Order {
    id: number;
    order_number: string;
    total_amount: number;
    status: string;
    created_at: string;
}

interface User {
    id: number;
    name: string;
    email: string;
    whatsapp: string;
}

interface DashboardProps {
    recentOrders: Order[];
    orderSummary: OrderSummary;
    user: User;
}

const props = defineProps<DashboardProps>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

// Format date
const formatDate = (dateString: string): string => {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    }).format(date);
};

// Format currency
const formatCurrency = (amount: number): string => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
};

// Get status label
const getStatusLabel = (status: string): string => {
    const labels: Record<string, string> = {
        'pending': 'Menunggu Konfirmasi',
        'processing': 'Sedang Diproses',
        'shipping': 'Dalam Pengiriman',
        'completed': 'Selesai',
        'cancelled': 'Dibatalkan'
    };

    return labels[status] || status;
};

// Get status color
const getStatusColor = (status: string): string => {
    const colors: Record<string, string> = {
        'pending': 'text-warning-500',
        'processing': 'text-primary-500',
        'shipping': 'text-blue-500',
        'completed': 'text-success-500',
        'cancelled': 'text-danger-500'
    };

    return colors[status] || 'text-slate-500';
};

// Get status icon
const getStatusIcon = (status: string) => {
    const icons: Record<string, any> = {
        'pending': Clock,
        'processing': Package,
        'shipping': Truck,
        'completed': CheckCircle,
        'cancelled': Clock
    };

    return icons[status] || Clock;
};
</script>

<template>
    <Head title="Dashboard Member" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-4 md:p-6">
            <!-- Welcome & User Info -->
            <div class="grid md:grid-cols-3 gap-6">
                <!-- Welcome Card -->
                <div class="md:col-span-2">
                    <Card class="h-full">
                        <CardHeader>
                            <CardTitle>Selamat Datang, {{ user.name }}!</CardTitle>
                            <CardDescription>
                                Ini adalah dashboard member Anda. Di sini Anda dapat memantau pesanan, produk, dan melihat status pembayaran.
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="flex flex-col items-center justify-center p-4 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                                    <div class="bg-warning-100 dark:bg-warning-900/30 p-2 rounded-full mb-2">
                                        <Clock class="h-4 w-4 text-warning-500 dark:text-warning-400" />
                                    </div>
                                    <span class="text-sm text-slate-500 dark:text-slate-400">Pending</span>
                                    <span class="text-2xl font-bold text-slate-900 dark:text-slate-50">{{ orderSummary.pending }}</span>
                                </div>

                                <div class="flex flex-col items-center justify-center p-4 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                                    <div class="bg-primary-100 dark:bg-primary-900/30 p-2 rounded-full mb-2">
                                        <Package class="h-4 w-4 text-primary-500 dark:text-primary-400" />
                                    </div>
                                    <span class="text-sm text-slate-500 dark:text-slate-400">Diproses</span>
                                    <span class="text-2xl font-bold text-slate-900 dark:text-slate-50">{{ orderSummary.processing }}</span>
                                </div>

                                <div class="flex flex-col items-center justify-center p-4 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                                    <div class="bg-blue-100 dark:bg-blue-900/30 p-2 rounded-full mb-2">
                                        <Truck class="h-4 w-4 text-blue-500 dark:text-blue-400" />
                                    </div>
                                    <span class="text-sm text-slate-500 dark:text-slate-400">Dikirim</span>
                                    <span class="text-2xl font-bold text-slate-900 dark:text-slate-50">{{ orderSummary.shipping }}</span>
                                </div>

                                <div class="flex flex-col items-center justify-center p-4 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                                    <div class="bg-success-100 dark:bg-success-900/30 p-2 rounded-full mb-2">
                                        <CheckCircle class="h-4 w-4 text-success-500 dark:text-success-400" />
                                    </div>
                                    <span class="text-sm text-slate-500 dark:text-slate-400">Selesai</span>
                                    <span class="text-2xl font-bold text-slate-900 dark:text-slate-50">{{ orderSummary.completed }}</span>
                                </div>
                            </div>

                            <div class="mt-6">
                                <Link :href="route('orders.index')" class="block w-full">
                                    <Button variant="default" class="w-full">
                                        Lihat Semua Pesanan
                                    </Button>
                                </Link>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- User Info Card -->
                <div>
                    <Card class="h-full">
                        <CardHeader>
                            <CardTitle>Informasi Akun</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-4">
                                <div class="flex items-start gap-3">
                                    <User class="h-5 w-5 text-slate-400 mt-0.5" />
                                    <div>
                                        <p class="text-sm text-slate-500 dark:text-slate-400">Nama</p>
                                        <p class="font-medium">{{ user.name }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <Phone class="h-5 w-5 text-slate-400 mt-0.5" />
                                    <div>
                                        <p class="text-sm text-slate-500 dark:text-slate-400">WhatsApp</p>
                                        <p class="font-medium">{{ user.whatsapp }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <Mail class="h-5 w-5 text-slate-400 mt-0.5" />
                                    <div>
                                        <p class="text-sm text-slate-500 dark:text-slate-400">Email</p>
                                        <p class="font-medium">{{ user.email }}</p>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                        <CardFooter>
                            <Link :href="route('profile.edit')" class="block w-full">
                                <Button variant="outline" class="w-full text-sm">
                                    Edit Profil
                                </Button>
                            </Link>
                        </CardFooter>
                    </Card>
                </div>
            </div>

            <!-- Recent Orders -->
            <Card>
                <CardHeader>
                    <div class="flex items-center justify-between">
                        <CardTitle>Pesanan Terbaru</CardTitle>
                        <Link :href="route('orders.index')" class="text-sm text-primary-600 hover:text-primary-700 flex items-center">
                            Lihat Semua
                            <ChevronRight class="h-4 w-4 ml-1" />
                        </Link>
                    </div>
                </CardHeader>
                <CardContent>
                    <div v-if="recentOrders && recentOrders.length > 0">
                        <div class="rounded-lg border overflow-hidden">
                            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                                <thead class="bg-slate-50 dark:bg-slate-800">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">No. Pesanan</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tanggal</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                                    <tr v-for="order in recentOrders" :key="order.id" class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900 dark:text-white">{{ order.order_number }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">{{ formatDate(order.created_at) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white">{{ formatCurrency(order.total_amount) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <component :is="getStatusIcon(order.status)" class="h-4 w-4 mr-1.5" :class="getStatusColor(order.status)" />
                                                <span class="text-sm" :class="getStatusColor(order.status)">{{ getStatusLabel(order.status) }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <Link :href="`/orders/${order.id}`" class="text-primary-600 hover:text-primary-900 dark:hover:text-primary-400">Detail</Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div v-else class="text-center py-8 text-slate-500 dark:text-slate-400">
                        <ShoppingBag class="h-12 w-12 mx-auto text-slate-300 dark:text-slate-600 mb-4" />
                        <p>Anda belum memiliki pesanan.</p>
                        <Button variant="outline" as="a" :href="route('products.index')" class="mt-4">
                            Mulai Belanja
                        </Button>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
