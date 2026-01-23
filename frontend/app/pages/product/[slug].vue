<template>
  <div class="min-h-screen bg-slate-50 py-12 px-4 md:px-8">
    <div
      v-if="product"
      class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12"
    >

      <!-- ===== IMAGES ===== -->
      <div class="space-y-4">

        <!-- CAROUSEL -->
        <div class="bg-slate-100 rounded-xl p-6">
          <UCarousel
            ref="carousel"
            :items="images"
            arrows
            class="w-full"
            @select="onSelect"
          >
            <template #default="{ item }">
              <div class="flex justify-center items-center h-[520px]">
                <img
                  :src="imageUrl(item.path)"
                  :alt="item.altText"
                  class="max-h-full object-contain"
                />
              </div>
            </template>
          </UCarousel>
        </div>

        <!-- MINIATURES -->
        <div class="flex justify-center gap-3">
          <button
            v-for="(img, index) in images"
            :key="img.id"
            @click="select(index)"
            class="p-1 rounded-md bg-white transition cursor-pointer"
            :class="
              activeIndex === index
                ? 'ring-2 ring-yellow-400'
                : 'hover:ring-2 hover:ring-gray-300'
            "
          >
            <img
              :src="imageUrl(img.path)"
              :alt="img.altText"
              class="w-16 h-16 object-cover rounded"
            />
          </button>
        </div>

      </div>

      <!-- ===== INFOS PRODUIT ===== -->
      <div class="space-y-6">

        <div>
          <h1 class="text-3xl font-bold text-gray-900">
            {{ product.title }}
          </h1>
          <p class="text-sm text-gray-500 mt-4">
            Catégorie :
            <span
              v-for="category in product.categories"
              :key="category.id"
              class="font-medium"
            >
              {{ category.name }}
            </span>
          </p>
        </div>

        <div>
          <h3 class="text-xs text-gray-400 uppercase mb-2">Description</h3>
          <p class="text-gray-600 leading-relaxed">
            {{ product.description }}
          </p>
        </div>

        <div>
          <h3 class="text-xs text-gray-400 uppercase mb-2">Prix</h3>
          <div class="text-3xl font-bold text-gray-900">
            {{ formatPrice(product.price) }}
          </div>
          <p class="text-sm text-gray-500 mt-1">Les prix incluent la TVA.</p>
        </div>

        <div>
          <h3 class="text-xs text-gray-400 uppercase mb-2">Disponibilité</h3>
          <p
            :class="product.stock > 0 ? 'text-green-600' : 'text-red-600'"
            class="font-semibold"
          >
            {{ product.stock > 0 ? 'En stock' : 'Rupture de stock' }}
            <span v-if="product.stock > 0">
              ({{ product.stock }})
            </span>
          </p>
        </div>

        <div class="flex gap-4 items-center">
          <select
            v-model="quantity"
            class="w-24 border rounded-md px-3 py-2 text-sm"
            :disabled="product.stock === 0"
          >
            <option
              v-for="i in product.stock"
              :key="i"
              :value="i"
            >
              {{ i < 10 ? '0' + i : i }}
            </option>
          </select>

          <button
            :disabled="product.stock === 0"
            class="bg-yellow-400 hover:bg-yellow-500 disabled:bg-gray-300 transition
                   text-white px-8 py-3 rounded-md font-semibold cursor-pointer"
            @click="addToCart"
          >
            Ajouter au panier
          </button>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { formatPrice } from '@/utils/formatPrice'
import CartService from '~/services/CartApi'

const route = useRoute()
const carousel = useTemplateRef('carousel')
const activeIndex = ref(0)
const quantity = ref(1)

const ui = useUiStore()

const config = useRuntimeConfig()
const baseUrl = config.public.NUXT_PUBLIC_API_BASE_URL || 'https://localhost:8000'

ui.showLoader()
const { data: product, error } = await useProductBySlug(route.params.slug)

if(error.value) {
  console.error("Erreur lors de la récupération du produit :", error.value)
}
ui.hideLoader()
/**
 * mainImage toujours en premier
 */
const images = computed(() => {
  if (!product.value) return []

  const main = product.value.mainImage
  const others = product.value.images.filter(
    img => img.id !== main.id
  )

  return [main, ...others]
})

function imageUrl(path) {
  return `${baseUrl}/${path}`
}

/**
 * Carousel → thumbnails
 */
function onSelect(index) {
  activeIndex.value = index
}

/**
 * Thumbnails → carousel
 */
function select(index) {
  activeIndex.value = index
  carousel.value?.emblaApi?.scrollTo(index)
}

const { $apiFetch } = useNuxtApp()
const cartService = CartService($apiFetch);
const toast = useToast();
const auth = useAuthStore();
const cartStore = useCartStore();

async function addToCart() {
  if (!auth.isAuthenticated) {
    toast.add({
      title: 'Veuillez vous connecter',
      description: "Vous devez être connecté pour ajouter des articles au panier.",
      color: 'warning'
    })
    return
  }
  try{
    ui.showLoader()
    await cartService.addProduct(product.value.id, quantity.value);
    ui.hideLoader()
    cartStore.fetchCount()
    toast.add({
      title: 'Votre article a été ajouté au panier',
      description: "Vous pouvez le consulter dans votre panier.",
      color: 'success'
    })
  } catch (err) {
    ui.hideLoader()
    console.error("Erreur ajout au panier :", err)
    toast.add({
      title: 'Erreur',
      description: "Impossible d'ajouter le produit au panier. Veuillez réessayer.",
      color: 'danger'
    })
    return
  }
}
</script>
