<template>
  <Head :title="product.name" />

  <MainLayout>
    <div class="bg-gray-50 py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <div class="bg-white p-4 rounded-lg shadow-sm mb-8">
          <Breadcrumb :items="breadcrumbItems" />
        </div>

        <!-- Product Detail -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
          <!-- Product Images -->
          <div class="bg-white rounded-xl shadow-sm p-6">
            <!-- Main Image with Lightbox trigger -->
            <div class="mb-6 overflow-hidden rounded-lg cursor-pointer relative group">
              <img 
                :src="selectedImage" 
                :alt="product.name" 
                class="w-full h-auto max-h-[500px] object-contain rounded-md transition-transform duration-300 group-hover:scale-[1.02]" 
                @click="openLightbox(selectedImageIndex)"
              />
              <div 
                class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center"
                @click="openLightbox(selectedImageIndex)"
              >
                <div class="bg-white/80 backdrop-blur-sm p-2 rounded-full">
                  <ExpandIcon class="w-6 h-6 text-gray-700" />
                </div>
              </div>
            </div>
            
            <!-- Thumbnail Gallery -->
            <div 
              v-if="product.gallery && product.gallery.length > 0" 
              class="grid grid-cols-5 xl:grid-cols-6 gap-3"
            >
              <!-- Featured Image Thumbnail -->
              <div 
                class="aspect-square overflow-hidden cursor-pointer rounded-md border-2 transition-all duration-200"
                :class="selectedImageIndex === -1 ? 'border-primary-600 scale-[1.05] shadow-md' : 'border-transparent hover:border-primary-300'"
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
                class="aspect-square overflow-hidden cursor-pointer rounded-md border-2 transition-all duration-200"
                :class="selectedImageIndex === index ? 'border-primary-600 scale-[1.05] shadow-md' : 'border-transparent hover:border-primary-300'"
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
          <div class="bg-white rounded-xl shadow-sm p-6">
            <!-- Title Produk -->
            <h1 class="text-2xl md:text-3xl font-bold mb-4">{{ product.name }}</h1>
            
            <!-- Kategori, Ketersediaan, Kode Produk -->
            <div class="flex flex-wrap items-center gap-2 mb-6">
              <Badge v-if="product.category" variant="outline" class="px-3 py-0.5 bg-gray-50">
                {{ product.category.name }}
              </Badge>
              <Badge 
                variant="outline" 
                :class="product.is_active ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200'"
                class="px-3 py-0.5"
              >
                {{ product.is_active ? 'Tersedia' : 'Tidak Tersedia' }}
              </Badge>
              <div class="border border-gray-200 rounded-md px-3 py-0.5 text-gray-600 text-sm flex items-center">
                <TagIcon class="w-3.5 h-3.5 mr-1.5 opacity-70" />
                {{ product.product_code || 'Tidak ada kode' }}
              </div>
            </div>
            
            <!-- Harga -->
            <div class="mb-5">
              <p class="text-sm text-gray-500 mb-1">Harga</p>
              <div class="flex items-baseline">
                <span class="text-3xl font-bold text-gray-800 mr-1">Rp</span>
                <span class="text-3xl font-bold text-gray-800">{{ formatPriceRaw(product.price) }}</span>
              </div>
            </div>
                       
            <!-- Deskripsi Produk -->
            <div class="mb-8">
              <h3 class="text-lg font-medium mb-3">Deskripsi Produk</h3>
              <div class="prose prose-sm max-w-none text-gray-600">
                <div v-html="product.description"></div>
              </div>
            </div>
            
            <!-- Fitur Produk -->
            <div v-if="hasProductFeatures" class="mb-8 bg-gray-50 rounded-lg p-5">
              <h3 class="text-lg font-medium mb-4 flex items-center">
                <CheckCircleIcon class="w-5 h-5 mr-2 text-gray-700" />
                Fitur Produk
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-3">
                <div v-for="(feature, index) in product.product_features" :key="index" class="flex items-start">
                  <CheckIcon class="w-4 h-4 text-primary-600 mr-2 mt-1 flex-shrink-0" />
                  <span>{{ feature }}</span>
                </div>
              </div>
            </div>
            
            <!-- Nilai/Keunggulan Produk -->
            <div v-if="hasProductValues" class="mb-8 bg-gray-50 rounded-lg p-5">
              <h3 class="text-lg font-medium mb-4 flex items-center">
                <StarIcon class="w-5 h-5 mr-2 text-amber-500" /> 
                Keunggulan Produk
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-3">
                <div v-for="(value, index) in product.product_values" :key="index" class="flex items-start">
                  <ArrowRightIcon class="w-4 h-4 text-amber-500 mr-2 mt-1 flex-shrink-0" />
                  <span>{{ value }}</span>
                </div>
              </div>
            </div>
            
           <!-- Tombol: Keranjang & Demo -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-8">
              <Button class="bg-red-500 hover:bg-red-600 text-white h-12 flex items-center justify-center">
                <ShoppingCartIcon class="w-5 h-5 mr-2" />
                Tambah ke Keranjang
              </Button>
              
              <Button 
                v-if="product.demo_url" 
                as="a"
                :href="product.demo_url" 
                target="_blank" 
                rel="noopener noreferrer"
                class="bg-blue-600 hover:bg-blue-700 text-white h-12 flex items-center justify-center"
              >
                <ExternalLinkIcon class="w-5 h-5 mr-2" />
                Lihat Demo
              </Button>
            </div>

            <!-- Share Product -->
            <div class="border-t border-gray-200 pt-5">
              <p class="text-sm font-medium mb-3">Bagikan produk ini:</p>
              <div class="flex items-center gap-2">
                <Button variant="outline" size="icon" @click="shareOnFacebook" class="rounded-full h-10 w-10 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                  <FacebookIcon class="w-5 h-5" />
                </Button>
                <Button variant="outline" size="icon" @click="shareOnTwitter" class="rounded-full h-10 w-10 hover:bg-blue-50 hover:text-blue-400 transition-colors">
                  <TwitterIcon class="w-5 h-5" />
                </Button>
                <Button variant="outline" size="icon" @click="shareOnWhatsApp" class="rounded-full h-10 w-10 hover:bg-green-50 hover:text-green-500 transition-colors">
                  <MessageCircleIcon class="w-5 h-5" />
                </Button>
                <Button variant="outline" size="icon" @click="copyLink" class="rounded-full h-10 w-10 hover:bg-gray-100 transition-colors">
                  <LinkIcon class="w-5 h-5" />
                </Button>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Related Products -->
        <div v-if="relatedProducts.length > 0" class="mb-8">
          <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold">Produk Terkait</h2>
            <Link :href="route('products.index')" class="text-primary-600 hover:text-primary-800 text-sm font-medium">Lihat Semua Produk</Link>
          </div>
          
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <div 
              v-for="product in relatedProducts" 
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
                  <h3 class="text-lg font-bold mb-2 line-clamp-2 group-hover:text-primary-600 transition-colors">{{ product.name }}</h3>
                  <p class="text-sm text-gray-500 mb-3 line-clamp-2">
                    {{ truncateDescription(product.description) }}
                  </p>
                  <div class="mt-auto flex justify-between items-center">
                    <div class="text-xl font-bold text-primary-600">
                      {{ formatPrice(product.price) }}
                    </div>
                    <Button variant="outline" size="sm" class="group-hover:bg-primary group-hover:text-white transition-colors">
                      Lihat Detail
                    </Button>
                  </div>
                </div>
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Lightbox Modal -->
    <div v-if="lightboxOpen" class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center" @click="closeLightbox">
      <div class="relative w-full max-w-6xl mx-auto px-4">
        <!-- Close Button -->
        <button 
          class="absolute top-4 right-4 z-50 bg-white/10 backdrop-blur-sm p-2 rounded-full hover:bg-white/20 transition-colors"
          @click.stop="closeLightbox"
        >
          <XIcon class="w-6 h-6 text-white" />
        </button>

        <!-- Navigation Buttons -->
        <button 
          v-if="hasMultipleImages"
          class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white/10 backdrop-blur-sm p-3 rounded-full hover:bg-white/20 transition-colors"
          @click.stop="prevImage"
        >
          <ChevronLeftIcon class="w-6 h-6 text-white" />
        </button>
        
        <button 
          v-if="hasMultipleImages"
          class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white/10 backdrop-blur-sm p-3 rounded-full hover:bg-white/20 transition-colors"
          @click.stop="nextImage"
        >
          <ChevronRightIcon class="w-6 h-6 text-white" />
        </button>

        <!-- Lightbox Image -->
        <div class="max-h-[90vh] flex items-center justify-center" @click.stop>
          <img 
            :src="lightboxImage" 
            :alt="product.name" 
            class="max-h-[85vh] max-w-full object-contain"
          />
        </div>

        <!-- Image Counter -->
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-black/50 text-white px-4 py-1 rounded-full text-sm">
          {{ lightboxImageIndex + 1 }} / {{ totalImages }}
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import Breadcrumb from '@/components/ui/breadcrumb.vue';
import MainLayout from '@/components/layout/MainLayout.vue';
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { 
  ShoppingCartIcon, 
  HeartIcon, 
  FacebookIcon,
  TwitterIcon,
  MessageCircleIcon,
  LinkIcon,
  ExpandIcon,
  XIcon,
  ChevronLeftIcon,
  ChevronRightIcon,
  CheckIcon,
  CheckCircleIcon,
  StarIcon,
  ArrowRightIcon,
  ExternalLinkIcon,
  TagIcon
} from 'lucide-vue-next';
import { toast } from 'vue-sonner';

const props = defineProps({
  product: Object,
  relatedProducts: Array
});

// Cek apakah produk memiliki fitur dan nilai
const hasProductFeatures = computed(() => {
  return props.product.product_features && 
         Array.isArray(props.product.product_features) && 
         props.product.product_features.length > 0;
});

const hasProductValues = computed(() => {
  return props.product.product_values && 
         Array.isArray(props.product.product_values) && 
         props.product.product_values.length > 0;
});

// Gambar yang dipilih untuk ditampilkan
const selectedImageIndex = ref(-1); // -1 berarti gambar utama

// Lightbox state
const lightboxOpen = ref(false);
const lightboxImageIndex = ref(-1);

// URL gambar yang sedang ditampilkan
const selectedImage = computed(() => {
  if (selectedImageIndex.value === -1) {
    return `/storage/${props.product.featured_image}`;
  } else {
    return `/storage/${props.product.gallery[selectedImageIndex.value].image}`;
  }
});

// URL gambar untuk lightbox
const lightboxImage = computed(() => {
  if (lightboxImageIndex.value === -1) {
    return `/storage/${props.product.featured_image}`;
  } else {
    return `/storage/${props.product.gallery[lightboxImageIndex.value].image}`;
  }
});

// Total jumlah gambar (featured + gallery)
const totalImages = computed(() => {
  return 1 + (props.product.gallery?.length || 0);
});

// Cek apakah ada lebih dari satu gambar
const hasMultipleImages = computed(() => {
  return totalImages.value > 1;
});

// Pilih gambar
const selectImage = (index) => {
  selectedImageIndex.value = index;
};

// Buka lightbox
const openLightbox = (index) => {
  lightboxImageIndex.value = index;
  lightboxOpen.value = true;
  document.body.style.overflow = 'hidden'; // Prevent scrolling
};

// Tutup lightbox
const closeLightbox = () => {
  lightboxOpen.value = false;
  document.body.style.overflow = ''; // Re-enable scrolling
};

// Navigasi gambar di lightbox
const nextImage = () => {
  if (lightboxImageIndex.value === -1) {
    if (props.product.gallery && props.product.gallery.length > 0) {
      lightboxImageIndex.value = 0;
    }
  } else if (lightboxImageIndex.value < props.product.gallery.length - 1) {
    lightboxImageIndex.value++;
  } else {
    lightboxImageIndex.value = -1;
  }
};

const prevImage = () => {
  if (lightboxImageIndex.value === -1) {
    if (props.product.gallery && props.product.gallery.length > 0) {
      lightboxImageIndex.value = props.product.gallery.length - 1;
    }
  } else if (lightboxImageIndex.value > 0) {
    lightboxImageIndex.value--;
  } else {
    lightboxImageIndex.value = -1;
  }
};

// Keyboard navigation
const handleKeydown = (e) => {
  if (!lightboxOpen.value) return;
  
  if (e.key === 'Escape') {
    closeLightbox();
  } else if (e.key === 'ArrowRight' && hasMultipleImages.value) {
    nextImage();
  } else if (e.key === 'ArrowLeft' && hasMultipleImages.value) {
    prevImage();
  }
};

// Set up and clean up event listeners
onMounted(() => {
  document.addEventListener('keydown', handleKeydown);
});

onBeforeUnmount(() => {
  document.removeEventListener('keydown', handleKeydown);
  // Make sure to reset body style if component is unmounted while lightbox is open
  document.body.style.overflow = '';
});

// Format harga dengan format currency
const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(price);
};

// Format harga tanpa symbol currency
const formatPriceRaw = (price) => {
  return new Intl.NumberFormat('id-ID', {
    minimumFractionDigits: 0
  }).format(price);
};

// Menghilangkan HTML dan memotong teks deskripsi
const truncateDescription = (html) => {
  if (!html) return '';
  // Hapus tag HTML
  const text = html.replace(/<[^>]*>/g, '');
  return text.length > 60 ? text.substring(0, 60) + '...' : text;
};

// Breadcrumb data
const breadcrumbItems = computed(() => {
  const items = [
    { label: 'Beranda', href: route('home') },
    { label: 'Produk', href: route('products.index') },
  ];

  if (props.product.category) {
    items.push({
      label: props.product.category.name,
      href: route('products.index', { category: props.product.category.slug })
    });
  }

  items.push({ label: props.product.name });
  
  return items;
});

// Social sharing functions
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

<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style> 