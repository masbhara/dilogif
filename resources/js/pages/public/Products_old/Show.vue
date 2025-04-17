<template>
  <Head :title="product.name" />

  <div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-600 mb-8">
      <Link :href="route('home')" class="hover:text-primary-600">Beranda</Link>
      <span>&rsaquo;</span>
      <Link :href="route('products.index')" class="hover:text-primary-600">Produk</Link>
      <span>&rsaquo;</span>
      <Link 
        v-if="product.category" 
        :href="route('products.index', { category: product.category.slug })" 
        class="hover:text-primary-600"
      >
        {{ product.category.name }}
      </Link>
      <template v-if="product.category">
        <span>&rsaquo;</span>
      </template>
      <span class="font-medium">{{ product.name }}</span>
    </div>

    <!-- Product Detail -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
      <!-- Product Images -->
      <div>
        <!-- Main Image -->
        <div class="mb-4 overflow-hidden rounded-lg">
          <img :src="selectedImage" :alt="product.name" class="w-full h-auto object-cover rounded-md" />
        </div>
        
        <!-- Thumbnail Gallery -->
        <div v-if="product.gallery && product.gallery.length > 0" class="grid grid-cols-5 gap-2">
          <!-- Featured Image Thumbnail -->
          <div 
            class="aspect-square overflow-hidden cursor-pointer rounded-md border-2"
            :class="selectedImageIndex === -1 ? 'border-primary-600' : 'border-transparent'"
            @click="selectImage(-1)"
          >
            <img 
              :src="`/storage/${product.featured_image}`" 
              :alt="product.name" 
              class="w-full h-full object-cover"
            />
          </div>
          
          <!-- Gallery Images -->
          <div 
            v-for="(image, index) in product.gallery" 
            :key="image.id" 
            class="aspect-square overflow-hidden cursor-pointer rounded-md border-2"
            :class="selectedImageIndex === index ? 'border-primary-600' : 'border-transparent'"
            @click="selectImage(index)"
          >
            <img 
              :src="`/storage/${image.image}`" 
              :alt="`${product.name} - Gambar ${index + 1}`" 
              class="w-full h-full object-cover"
            />
          </div>
        </div>
      </div>
      
      <!-- Product Info -->
      <div>
        <h1 class="text-3xl font-bold mb-2">{{ product.name }}</h1>
        
        <div class="flex items-center gap-2 mb-4">
          <Badge v-if="product.category" variant="outline">{{ product.category.name }}</Badge>
          <Badge variant="secondary">{{ product.is_active ? 'Tersedia' : 'Tidak Tersedia' }}</Badge>
        </div>
        
        <div class="text-2xl font-bold text-primary-600 mb-6">
          {{ formatPrice(product.price) }}
        </div>
        
        <div class="prose prose-sm mb-6">
          <div v-html="product.description"></div>
        </div>
        
        <div class="flex items-center gap-4 mb-8">
          <Button>
            <ShoppingCartIcon class="w-4 h-4 mr-2" />
            Tambah ke Keranjang
          </Button>
          
          <Button variant="outline">
            <HeartIcon class="w-4 h-4 mr-2" />
            Tambah ke Wishlist
          </Button>
        </div>
        
        <div class="border-t border-gray-200 pt-4">
          <div class="text-sm text-gray-600">
            <div>Kode Produk: <span class="font-medium">{{ product.id }}</span></div>
            <div v-if="product.custom_url">URL Khusus: <span class="font-medium">{{ product.custom_url }}</span></div>
          </div>
          
          <!-- Share Links -->
          <div class="mt-4">
            <p class="text-sm font-medium mb-2">Bagikan:</p>
            <div class="flex items-center gap-2">
              <Button variant="ghost" size="icon" @click="shareOnFacebook">
                <FacebookIcon class="w-5 h-5" />
              </Button>
              <Button variant="ghost" size="icon" @click="shareOnTwitter">
                <TwitterIcon class="w-5 h-5" />
              </Button>
              <Button variant="ghost" size="icon" @click="shareOnWhatsApp">
                <MessageCircleIcon class="w-5 h-5" />
              </Button>
              <Button variant="ghost" size="icon" @click="copyLink">
                <LinkIcon class="w-5 h-5" />
              </Button>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Related Products -->
    <div v-if="relatedProducts.length > 0" class="border-t border-gray-200 pt-8 mt-8">
      <h2 class="text-2xl font-bold mb-6">Produk Terkait</h2>
      
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <div v-for="product in relatedProducts" :key="product.id" class="border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300">
          <Link :href="product.url" class="block">
            <div class="aspect-square overflow-hidden bg-gray-100">
              <img 
                :src="`/storage/${product.featured_image}`" 
                :alt="product.name" 
                class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
              />
            </div>
            <div class="p-4">
              <h3 class="text-lg font-semibold mb-2 line-clamp-2">{{ product.name }}</h3>
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
    </div>
  </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { ref, computed } from 'vue';
import { 
  ShoppingCartIcon, 
  HeartIcon, 
  FacebookIcon,
  TwitterIcon,
  MessageCircleIcon,
  LinkIcon
} from 'lucide-vue-next';
import { toast } from 'vue-sonner';

const props = defineProps({
  product: Object,
  relatedProducts: Array
});

// Gambar yang dipilih untuk ditampilkan
const selectedImageIndex = ref(-1); // -1 berarti gambar utama

// URL gambar yang sedang ditampilkan
const selectedImage = computed(() => {
  if (selectedImageIndex.value === -1) {
    return `/storage/${props.product.featured_image}`;
  } else {
    return `/storage/${props.product.gallery[selectedImageIndex.value].image}`;
  }
});

// Memilih gambar untuk ditampilkan
const selectImage = (index) => {
  selectedImageIndex.value = index;
};

// Format harga
const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(price);
};

// Share functions
const shareOnFacebook = () => {
  const url = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(window.location.href)}`;
  window.open(url, '_blank');
};

const shareOnTwitter = () => {
  const text = `Lihat produk ${props.product.name}`;
  const url = `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(window.location.href)}`;
  window.open(url, '_blank');
};

const shareOnWhatsApp = () => {
  const text = `Lihat produk ${props.product.name}: ${window.location.href}`;
  const url = `https://wa.me/?text=${encodeURIComponent(text)}`;
  window.open(url, '_blank');
};

const copyLink = () => {
  navigator.clipboard.writeText(window.location.href).then(() => {
    toast.success('Link berhasil disalin!');
  });
};
</script> 