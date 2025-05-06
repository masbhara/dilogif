<template>
    <Head title="Detail Template WhatsApp" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4 md:p-6">
            <!-- Header dengan judul dan tombol aksi -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h1 class="text-2xl font-bold text-secondary-900 dark:text-white">Detail Template WhatsApp</h1>
                <div class="flex gap-2">
                    <Link :href="route('admin.notifications.index')">
                        <Button variant="outline" class="flex items-center gap-1.5">
                            <ArrowLeftIcon class="h-4 w-4" />
                            Kembali
                        </Button>
                    </Link>
                    <Link :href="route('admin.notifications.edit', template.id)">
                        <Button variant="action" class="flex items-center gap-1.5">
                            <PencilIcon class="h-4 w-4" />
                            Edit Template
                        </Button>
                    </Link>
                </div>
            </div>
            
            <!-- Template Basic Info -->
            <Card class="border border-slate-200 dark:border-slate-700">
                <CardHeader>
                    <CardTitle>Informasi Template</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Nama Template</h3>
                            <p class="text-base font-medium text-slate-900 dark:text-white">{{ template.name }}</p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Tipe</h3>
                            <Badge variant="outline" class="px-2 py-1">
                                {{ template.type === 'customer' ? 'Template Pelanggan' : 'Template Admin' }}
                            </Badge>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Status Pemicu</h3>
                            <p class="text-base font-medium text-slate-900 dark:text-white">
                                {{ triggerStatusOptions[template.trigger_status] || template.trigger_status }}
                            </p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Status</h3>
                            <Badge :variant="template.is_active ? 'success' : 'destructive'" class="px-2 py-1">
                                {{ template.is_active ? 'Aktif' : 'Nonaktif' }}
                            </Badge>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Urutan</h3>
                            <p class="text-base font-medium text-slate-900 dark:text-white">{{ template.order }}</p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Terakhir Diperbarui</h3>
                            <p class="text-base font-medium text-slate-900 dark:text-white">
                                {{ formatDate(template.updated_at) }}
                            </p>
                        </div>
                    </div>
                </CardContent>
            </Card>
            
            <!-- Template Message -->
            <Card class="border border-slate-200 dark:border-slate-700">
                <CardHeader>
                    <CardTitle>Template Pesan</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="p-4 bg-slate-50 dark:bg-slate-800 rounded-md">
                        <pre class="whitespace-pre-wrap break-words text-sm text-slate-800 dark:text-slate-200">{{ template.message_template }}</pre>
                    </div>
                    
                    <!-- Deskripsi -->
                    <div v-if="template.description" class="mt-4">
                        <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Deskripsi</h3>
                        <p class="text-slate-700 dark:text-slate-300">{{ template.description }}</p>
                    </div>
                    
                    <!-- Variabel yang tersedia -->
                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Variabel yang tersedia</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                            <div 
                                v-for="(description, variable) in availableVariables" 
                                :key="variable" 
                                class="text-xs bg-slate-100 dark:bg-slate-700 p-2 rounded flex items-center justify-between"
                            >
                                <code class="text-primary-500 dark:text-primary-400">{{ variable }}</code>
                                <span class="text-slate-500 dark:text-slate-400">{{ description }}</span>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
            
            <!-- Preview Message -->
            <Card class="border border-slate-200 dark:border-slate-700">
                <CardHeader>
                    <CardTitle>Preview Tampilan Pesan</CardTitle>
                    <CardDescription>Perkiraan tampilan pesan di WhatsApp dengan variabel yang telah diisi</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                        <div class="flex items-center mb-2">
                            <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center mr-2">
                                <MessageCircleIcon class="h-4 w-4 text-white" />
                            </div>
                            <h4 class="font-medium text-green-900 dark:text-green-400">Preview WhatsApp</h4>
                        </div>
                        <div class="ml-10 p-3 bg-white dark:bg-slate-800 rounded-lg shadow-sm">
                            <div class="whitespace-pre-wrap break-words text-sm">
                                {{ formattedTemplate }}
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardHeader, CardTitle, CardContent, CardDescription } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { ArrowLeftIcon, PencilIcon, MessageCircleIcon } from 'lucide-vue-next';

const props = defineProps({
    template: Object,
    availableVariables: Object,
    triggerStatusOptions: Object,
});

// Breadcrumbs
const breadcrumbs = [
    {
        title: 'Admin',
        href: route('admin.dashboard'),
    },
    {
        title: 'Template WhatsApp',
        href: route('admin.notifications.index'),
    },
    {
        title: 'Detail Template',
        href: route('admin.notifications.show', props.template.id),
    },
];

// Format date helper
const formatDate = (dateString) => {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    }).format(date);
};

// Replace variables with example values for preview
const formattedTemplate = computed(() => {
    let result = props.template.message_template;
    
    const exampleValues = {
        '{order_number}': 'ORD20230615001',
        '{order_date}': '15/06/2023 14:30',
        '{customer_name}': 'Ahmad Fauzi',
        '{total_amount}': 'Rp 350.000',
        '{payment_method}': 'Bank Transfer',
        '{status}': 'Sedang Diproses',
        '{items_list}': '- 1x Produk Digital A: Rp 150.000\n- 2x Produk Digital B: Rp 200.000',
        '{subtotal}': 'Rp 350.000',
        '{admin_fee}': 'Rp 0',
        '{discount}': 'Rp 0',
    };
    
    Object.entries(exampleValues).forEach(([variable, value]) => {
        result = result.replace(new RegExp(variable, 'g'), value);
    });
    
    return result;
});
</script> 