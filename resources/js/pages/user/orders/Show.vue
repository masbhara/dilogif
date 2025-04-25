<script setup lang="ts">
import { defineComponent as _defineComponent } from "vue";
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle, CardDescription, CardFooter } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { 
    ShoppingBag, Package, Truck, CheckCircle, Clock, 
    ArrowLeft, FileText, User, Phone, Mail
} from 'lucide-vue-next';

interface Product {
    id: number;
    name: string;
    featured_image: string;
    category?: {
        id: number;
        name: string;
    };
}

interface OrderItem {
    id: number;
    order_id: number;
    product_id: number;
    quantity: number;
    price: number;
    subtotal: number;
    product: Product;
}

interface Payment {
    id: number;
    order_id: number;
    amount: number;
    status: string;
    payment_method: string;
    payment_date: string;
}

interface Order {
    id: number;
    order_number: string;
    user_id: number;
    customer_name: string;
    customer_phone: string;
    customer_email: string;
    subtotal: number;
    admin_fee: number;
    discount: number;
    total_amount: number;
    status: string;
    notes: string;
    created_at: string;
    updated_at: string;
    items: OrderItem[];
    payment?: Payment;
}

interface Props {
    order: Order;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
    },
    {
        title: 'Pesanan',
        href: route('orders.index'),
    },
    {
        title: `Order #${props.order.order_number}`,
        href: route('orders.show', props.order.id),
    },
];

// Format date
const formatDate = (dateString: string): string => {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    }).format(date);
};

// Format currency
const formatPrice = (amount: number): string => {
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

// Get status color class
const getStatusColor = (status: string): string => {
    const colors: Record<string, string> = {
        'pending': 'border-yellow-400 text-yellow-600 bg-yellow-50 dark:bg-yellow-900/30 dark:text-yellow-300 dark:border-yellow-700',
        'processing': 'border-blue-400 text-blue-600 bg-blue-50 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-700',
        'shipping': 'border-indigo-400 text-indigo-600 bg-indigo-50 dark:bg-indigo-900/30 dark:text-indigo-300 dark:border-indigo-700',
        'completed': 'border-green-400 text-green-600 bg-green-50 dark:bg-green-900/30 dark:text-green-300 dark:border-green-700',
        'cancelled': 'border-red-400 text-red-600 bg-red-50 dark:bg-red-900/30 dark:text-red-300 dark:border-red-700'
    };

    return colors[status] || 'border-gray-400 text-gray-600 bg-gray-50 dark:bg-gray-900/30 dark:text-gray-300 dark:border-gray-700';
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
    <Head title="Detail Pesanan" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-4 md:p-6">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl md:text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                        Order #{{ order.order_number }}
                    </h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ formatDate(order.created_at) }}</p>
                </div>
                
                <Badge 
                    variant="outline" 
                    class="text-sm md:text-base px-3 py-1.5 self-start md:self-auto"
                    :class="getStatusColor(order.status)"
                >
                    <component :is="getStatusIcon(order.status)" class="h-4 w-4 mr-1.5" />
                    {{ getStatusLabel(order.status) }}
                </Badge>
            </div>
            
            <!-- Back Button -->
            <div>
                <Link :href="route('orders.index')" class="inline-flex items-center text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
                    <ArrowLeft class="h-4 w-4 mr-1" />
                    Kembali ke Daftar Pesanan
                </Link>
            </div>
            
            <!-- Order Details -->
            <div class="grid md:grid-cols-3 gap-6">
                <!-- Customer Info -->
                <div class="md:col-span-1">
                    <Card class="h-full">
                        <CardHeader>
                            <CardTitle>Informasi Pemesan</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-4">
                                <div class="flex items-start gap-3">
                                    <User class="h-5 w-5 text-slate-400 mt-0.5" />
                                    <div>
                                        <p class="text-sm text-slate-500 dark:text-slate-400">Nama</p>
                                        <p class="font-medium">{{ order.customer_name }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <Phone class="h-5 w-5 text-slate-400 mt-0.5" />
                                    <div>
                                        <p class="text-sm text-slate-500 dark:text-slate-400">WhatsApp</p>
                                        <p class="font-medium">{{ order.customer_phone }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <Mail class="h-5 w-5 text-slate-400 mt-0.5" />
                                    <div>
                                        <p class="text-sm text-slate-500 dark:text-slate-400">Email</p>
                                        <p class="font-medium">{{ order.customer_email }}</p>
                                    </div>
                                </div>
                                <div v-if="order.notes" class="flex items-start gap-3">
                                    <FileText class="h-5 w-5 text-slate-400 mt-0.5" />
                                    <div>
                                        <p class="text-sm text-slate-500 dark:text-slate-400">Catatan</p>
                                        <p class="font-medium">{{ order.notes }}</p>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
                
                <!-- Order Items -->
                <div class="md:col-span-2">
                    <Card>
                        <CardHeader>
                            <CardTitle>Detail Produk</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <!-- Products Table -->
                            <div class="rounded-lg border overflow-hidden">
                                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                                    <thead class="bg-slate-50 dark:bg-slate-800">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Produk</th>
                                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Harga</th>
                                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Jumlah</th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                                        <tr v-for="item in order.items" :key="item.id" class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <div class="h-12 w-12 flex-shrink-0 overflow-hidden rounded-md border border-slate-200 dark:border-slate-700">
                                                        <img 
                                                            :src="`/storage/${item.product.featured_image}`" 
                                                            :alt="item.product.name" 
                                                            class="h-full w-full object-cover object-center"
                                                        />
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-slate-900 dark:text-white">{{ item.product.name }}</div>
                                                        <div v-if="item.product.category" class="text-xs text-slate-500 dark:text-slate-400">{{ item.product.category.name }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-slate-500 dark:text-slate-400">
                                                {{ formatPrice(item.price) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-slate-500 dark:text-slate-400">
                                                {{ item.quantity }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-slate-900 dark:text-white">
                                                {{ formatPrice(item.subtotal) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Order Summary -->
                            <div class="mt-4 rounded-lg border p-4 divide-y divide-slate-200 dark:divide-slate-700">
                                <div class="space-y-2 pb-4">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-500 dark:text-slate-400">Subtotal</span>
                                        <span class="font-medium text-slate-900 dark:text-white">{{ formatPrice(order.subtotal) }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-500 dark:text-slate-400">Biaya Admin</span>
                                        <span class="font-medium text-slate-900 dark:text-white">{{ formatPrice(order.admin_fee) }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-500 dark:text-slate-400">Diskon</span>
                                        <span class="font-medium text-green-600 dark:text-green-400">-{{ formatPrice(order.discount) }}</span>
                                    </div>
                                </div>
                                <div class="pt-4">
                                    <div class="flex justify-between">
                                        <span class="text-base font-medium text-slate-900 dark:text-white">Total</span>
                                        <span class="text-base font-bold text-primary-600 dark:text-primary-400">{{ formatPrice(order.total_amount) }}</span>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template> 