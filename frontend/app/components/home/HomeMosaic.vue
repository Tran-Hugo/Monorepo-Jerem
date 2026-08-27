<template>
  <!-- Grille Mosaïque Artistique 5x3 -->
  <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-2.5 sm:gap-3.5 bg-white/50 p-3 sm:p-4 rounded-2xl sm:rounded-3xl shadow-xl backdrop-blur-xs border border-white/70">
    <NuxtLink
      v-for="tile in renderedTiles"
      :key="tile.slot"
      :to="`/product/${tile.slug}`"
      :class="[
        tile.gridClass,
        'relative group overflow-hidden rounded-xl sm:rounded-2xl shadow-sm hover:shadow-2xl transition-all duration-300 block'
      ]"
    >
      <img
        :src="getImageUrl(tile.image)"
        :alt="tile.title"
        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
        :loading="tile.loading"
      />

      <!-- Overlay grande tuile (Slots 1 et 9) -->
      <div
        v-if="tile.isLarge"
        class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/25 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4 sm:p-6 text-white"
      >
        <span class="text-[10px] sm:text-xs uppercase tracking-widest text-amber-400 font-semibold mb-1">
          {{ tile.tag }}
        </span>
        <h3 class="text-base sm:text-xl font-bold leading-tight">{{ tile.title }}</h3>
        <div class="flex items-center justify-between mt-2 pt-2 border-t border-white/20">
          <span class="text-sm sm:text-base font-black text-white">{{ formatPrice(tile.price) }}</span>
          <span class="text-xs text-amber-300 font-medium inline-flex items-center gap-1">
            Découvrir
            <svg class="w-3.5 h-3.5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </span>
        </div>
      </div>

      <!-- Overlay petite tuile (Slots 2 à 8) -->
      <div
        v-else
        class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-3 text-white"
      >
        <h3 class="text-xs sm:text-sm font-bold truncate">{{ tile.title }}</h3>
        <span class="text-xs font-semibold text-amber-400">{{ formatPrice(tile.price) }}</span>
      </div>
    </NuxtLink>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { formatPrice } from '@/utils/formatPrice.js'

const props = defineProps({
  products: {
    type: Array,
    default: () => []
  }
})

const config = useRuntimeConfig()
const baseUrl = config.public.NUXT_PUBLIC_API_BASE_URL || 'https://api.ton-domaine.local'

const getImageUrl = (path) => {
  if (!path) return ''
  if (path.startsWith('http') || path.startsWith('/')) return path
  return `${baseUrl}/${path}`
}

// Configuration des 9 slots de la mosaïque 5x3 (avec données de secours pour le cas base vide)
const SLOT_CONFIGS = [
  {
    slot: 1,
    gridClass: 'col-span-2 row-span-2 md:col-start-1 md:col-end-3 md:row-start-1 md:row-end-3 aspect-square bg-[#0b1329]',
    isLarge: true,
    defaultTag: 'Tirage Vedette',
    loading: 'eager',
    fallback: {
      title: "Magi'Sushi",
      slug: 'magi-sushi',
      price: 1099,
      image: 'uploads/images/6956a62c3a2859.36011156.png',
      tag: 'Tirage Vedette'
    }
  },
  {
    slot: 2,
    gridClass: 'col-span-1 row-span-1 md:col-start-3 md:col-end-4 md:row-start-1 md:row-end-2 aspect-square bg-neutral-900',
    isLarge: false,
    loading: 'lazy',
    fallback: {
      title: 'Kyode',
      slug: 'kyode',
      price: 3599,
      image: 'uploads/images/6956a66f7bfee4.16521102.png'
    }
  },
  {
    slot: 3,
    gridClass: 'col-span-1 row-span-1 md:col-start-4 md:col-end-5 md:row-start-1 md:row-end-2 aspect-square bg-white border border-neutral-100',
    isLarge: false,
    loading: 'lazy',
    fallback: {
      title: 'Simba',
      slug: 'simba',
      price: 200,
      image: 'uploads/images/6956a5a3130632.25325515.jpg'
    }
  },
  {
    slot: 4,
    gridClass: 'col-span-1 row-span-1 md:col-start-5 md:col-end-6 md:row-start-1 md:row-end-2 aspect-square bg-[#fef5d8]',
    isLarge: false,
    loading: 'lazy',
    fallback: {
      title: 'Seiko - Gear 5th',
      slug: 'seiko-gear-5th',
      price: 35099,
      image: 'uploads/images/68ad288d2011e5.00434039.webp'
    }
  },
  {
    slot: 5,
    gridClass: 'col-span-1 row-span-1 md:col-start-3 md:col-end-4 md:row-start-2 md:row-end-3 aspect-square bg-white border border-neutral-100',
    isLarge: false,
    loading: 'lazy',
    fallback: {
      title: "Sket'ka",
      slug: 'sket-ka',
      price: 799,
      image: 'uploads/images/69596913322271.93039379.png'
    }
  },
  {
    slot: 6,
    gridClass: 'col-span-1 row-span-1 md:col-start-1 md:col-end-2 md:row-start-3 md:row-end-4 aspect-square bg-[#3d3d3d]',
    isLarge: false,
    loading: 'lazy',
    fallback: {
      title: 'Mardi Soir',
      slug: 'mardi-soir',
      price: 999,
      image: 'uploads/images/695723f6678c22.60312203.png'
    }
  },
  {
    slot: 7,
    gridClass: 'col-span-1 row-span-1 md:col-start-2 md:col-end-3 md:row-start-3 md:row-end-4 aspect-square bg-white border border-neutral-100',
    isLarge: false,
    loading: 'lazy',
    fallback: {
      title: 'Octopus',
      slug: 'octopus',
      price: 2299,
      image: 'uploads/images/6957257162ede6.35747644.png'
    }
  },
  {
    slot: 8,
    gridClass: 'col-span-1 row-span-1 md:col-start-3 md:col-end-4 md:row-start-3 md:row-end-4 aspect-square bg-neutral-900',
    isLarge: false,
    loading: 'lazy',
    fallback: {
      title: 'Coca-cola Bubble',
      slug: 'coca-cola-bubble',
      price: 1500,
      image: 'uploads/images/69595c70db35f9.37553541.png'
    }
  },
  {
    slot: 9,
    gridClass: 'col-span-2 row-span-2 md:col-start-4 md:col-end-6 md:row-start-2 md:row-end-4 aspect-square bg-neutral-900',
    isLarge: true,
    defaultTag: 'Pièce Maîtresse',
    loading: 'lazy',
    fallback: {
      title: 'Wonder Waves',
      slug: 'wonder-waves',
      price: 9999,
      image: 'uploads/images/695724a20ad5e7.94791132.png',
      tag: 'Pièce Maîtresse'
    }
  }
]

const renderedTiles = computed(() => {
  const dynamicProducts = Array.isArray(props.products) ? props.products : []

  return SLOT_CONFIGS.map((slotConfig, index) => {
    const product = dynamicProducts[index]
    if (product) {
      const imgPath = product.mainImage?.path || product.images?.[0]?.path
      const categoryName = product.categories?.[0]?.name
      return {
        ...slotConfig,
        title: product.title,
        slug: product.slug,
        price: product.price,
        image: imgPath || slotConfig.fallback.image,
        tag: categoryName || slotConfig.defaultTag || ''
      }
    }
    // Fallback gracieux si la base est vide ou a moins de 9 produits
    return {
      ...slotConfig,
      ...slotConfig.fallback
    }
  })
})
</script>

