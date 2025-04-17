<template>
  <Head title="Produk" />
  
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 text-center">Katalog Produk</h1>
    
    <!-- Filter -->
    <div class="mb-8">
      <div class="flex flex-wrap items-center gap-4">
        <div class="flex-1">
          <input 
            v-model="search" 
            type="text" 
            placeholder="Cari produk..." 
            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
            @keyup.enter="filterProducts"
          />
        </div>
        <div>
          <select 
            v-model="selectedCategory" 
            class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
            @change="filterProducts"
          >
            <option value="">Semua Kategori</option>
            <option v-for="category in categories" :key="category.id" :value="category.slug">
              {{ category.name }}
            </option>
          </select>
        </div>
        <Button @click="filterProducts">Filter</Button>
      </div>
    </div>
    
    <!-- Products Grid -->
    <div v-if="products.data.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      <div v-for="product in products.data" :key="product.id" class="border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300">
        <Link :href="product.url" class="block">
          <div class="aspect-square overflow-hidden bg-gray-100">
            <img 
              :src="`/storage/${product.featured_image}`" 
              :alt="product.name" 
              class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
            />
          </div>
          <div class="p-4">
            <h2 class="text-lg font-semibold mb-2 line-clamp-2">{{ product.name }}</h2>
            <div class="text-sm text-gray-500 mb-2" v-if="product.category">
              {{ product.category.name }}
            </div>
            <div class="flex justify-between items-center">
              <div class="text-primary-600 font-bold">
                {{ formatPrice(product.price) }}
              </div>
              <Button variant="outline" size="sm">Lihat Detail</Button>
            </div>
          </div>
        </Link>
      </div>
    </div>
    
    <!-- Empty State -->
    <div v-else class="text-center py-12">
      <div class="text-5xl mb-4">😔</div>
      <h3 class="text-xl font-semibold mb-2">Tidak ada produk ditemukan</h3>
      <p class="text-gray-500 mb-4">Coba ubah filter pencarian atau kategori Anda</p>
      <Button @click="resetFilters">Reset Filter</Button>
    </div>
    
    <!-- Pagination -->
    <div v-if="products.data.length > 0" class="mt-8">
      <Pagination :links="products.links" />
    </div>
  </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import Pagination from '@/components/Pagination.vue';
import { ref, computed, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  products: Object,
  categories: Array,
  filters: Object
});

const search = ref(props.filters.search || '');
const selectedCategory = ref(props.filters.category || '');

const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(price);
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