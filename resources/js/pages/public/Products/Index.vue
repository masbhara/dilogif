<template>
  <Head title="Katalog Produk" />
  
  <MainLayout>
    <div class="bg-gray-50 py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 text-center">
          <h1 class="text-4xl font-bold mb-2">Katalog Produk</h1>
          <p class="text-gray-600 max-w-2xl mx-auto">Temukan koleksi produk terbaik kami dengan beragam pilihan yang sesuai kebutuhan Anda</p>
        </div>
        
        
        <!-- Filter -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
          <div class="flex flex-col md:flex-row items-stretch gap-4">
            <!-- Pencarian -->
            <div class="flex-1">
              <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Produk</label>
              <div class="relative">
                <input 
                  id="search"
                  v-model="search" 
                  type="text" 
                  placeholder="Masukkan nama produk..." 
                  class="w-full pl-10 px-4 py-2 h-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
                  @keyup.enter="filterProducts"
                />
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                  </svg>
                </div>
              </div>
            </div>
            
            <!-- Kategori -->
            <div class="w-full md:w-72">
              <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
              <select 
                id="category"
                v-model="selectedCategory" 
                class="w-full px-4 py-2 h-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
                @change="filterProducts"
              >
                <option value="">Semua Kategori</option>
                <option v-for="category in categories" :key="category.id" :value="category.slug">
                  {{ category.name }}
                </option>
              </select>
            </div>
            
            <!-- Tombol -->
            <div class="flex items-end gap-2 mt-auto">
              <Button @click="filterProducts" class="bg-gray-900 hover:bg-black h-10 px-5">Filter Produk</Button>
              <Button @click="resetFilters" variant="outline" class="h-10">Reset</Button>
            </div>
          </div>
        </div>
        
        <!-- Products Grid -->
        <div v-if="products.data.length > 0">
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <div 
              v-for="product in products.data" 
              :key="product.id" 
              class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 group"
            >
              <Link :href="product.url" class="block h-full flex flex-col">
                <div class="aspect-square overflow-hidden bg-gray-100 relative">
                  <img 
                    :src="`/storage/${product.featured_image}`" 
                    :alt="product.name" 
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                  />
                  <div v-if="product.category" class="absolute top-2 left-2">
                    <Badge variant="primary" class="bg-primary/80 backdrop-blur-sm">{{ product.category.name }}</Badge>
                  </div>
                </div>
                <div class="p-5 flex flex-col flex-grow">
                  <h2 class="text-lg font-bold mb-2 line-clamp-2 group-hover:text-primary-600 transition-colors">{{ product.name }}</h2>
                  <p class="text-sm text-gray-500 mb-3 line-clamp-2">
                    {{ truncateDescription(product.description) }}
                  </p>
                  <div class="mt-auto flex justify-between items-center">
                    <div class="text-xl font-bold text-primary-600">
                      {{ formatPrice(product.price) }}
                    </div>
                    <Button variant="outline" size="sm" class="group-hover:bg-primary group-hover:text-white transition-colors cursor-pointer">
                      Lihat Detail
                    </Button>
                  </div>
                </div>
              </Link>
            </div>
          </div>
          
          <!-- Pagination -->
          <div class="mt-12">
            <Pagination :links="products.links" />
          </div>
        </div>
        
        <!-- Empty State -->
        <div v-else class="text-center py-16 bg-white rounded-xl shadow-sm">
          <div class="text-6xl mb-4">😔</div>
          <h3 class="text-2xl font-semibold mb-2">Tidak ada produk ditemukan</h3>
          <p class="text-gray-500 mb-6 max-w-md mx-auto">Coba ubah filter pencarian atau kategori Anda untuk menemukan produk yang Anda cari</p>
          <Button @click="resetFilters">Reset Filter</Button>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import Pagination from '@/components/Pagination.vue';
import MainLayout from '@/components/layout/MainLayout.vue';
import { ref, computed, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  products: Object,
  categories: Array,
  filters: Object
});

const search = ref(props.filters?.search || '');
const selectedCategory = ref(props.filters?.category || '');

const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(price);
};

const truncateDescription = (html) => {
  if (!html) return '';
  // Hapus tag HTML
  const text = html.replace(/<[^>]*>/g, '');
  return text.length > 60 ? text.substring(0, 60) + '...' : text;
};

const filterProducts = () => {
  router.get(route('products.index'), {
    search: search.value,
    category: selectedCategory.value
  }, {
    preserveState: true,
    replace: true
  });
};

const resetFilters = () => {
  search.value = '';
  selectedCategory.value = '';
  filterProducts();
};

// Set nilai filter dari URL saat komponenen di-mount
onMounted(() => {
  if (props.filters) {
    search.value = props.filters.search || '';
    selectedCategory.value = props.filters.category || '';
  }
});


</script> 