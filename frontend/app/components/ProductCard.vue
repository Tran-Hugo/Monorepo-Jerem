<template>
  <div class="bg-white/50 w-54 overflow-hidden">
    <NuxtLink :to="`/product/${product.slug}`">
      <img
        :src="baseUrl + '/' + product?.mainImage?.path"
        :alt="product.title"
        class="h-54 w-54 object-cover"
      />
      <div class="py-4">
        <h3 class="text-lg font-semibold mb-1">
          {{ product.title }}
        </h3>
        <p class="text-gray-700 font-medium">
          {{ formatPrice(product.price) }}
        </p>
      </div>
    </NuxtLink>
  </div>
</template>

<script setup>
import { formatPrice } from '@/utils/formatPrice.js'

const { product } = defineProps({
  product: {
    type: Object,
    required: true
  }
})

const config = useRuntimeConfig()
const baseUrl =
  config.public.NUXT_PUBLIC_API_BASE_URL || 'https://localhost:8000'
</script>
