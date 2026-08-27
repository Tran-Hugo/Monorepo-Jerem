<template>
  <div class="flex items-center justify-between gap-4 w-full">
    <div class="flex items-center gap-4">
      <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl bg-neutral-100 border border-neutral-200/80 p-2 flex items-center justify-center flex-shrink-0 overflow-hidden">
        <img
          v-if="shipping?.image?.path"
          :src="`${baseUrl}/${shipping.image.path}`"
          :alt="shipping.image.altTxt || shipping.name"
          class="w-full h-full object-contain"
        />
        <svg v-else class="w-6 h-6 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
        </svg>
      </div>

      <div>
        <h3 class="font-bold text-neutral-900 text-sm sm:text-base">
          {{ shipping.name }}
        </h3>
        <p v-if="shipping.description" class="text-xs text-neutral-500 line-clamp-1 mt-0.5">
          {{ shipping.description }}
        </p>
      </div>
    </div>

    <div class="text-right flex-shrink-0">
      <span class="text-base sm:text-lg font-bold text-neutral-950">
        {{ formatPrice(shipping.price) }}
      </span>
    </div>
  </div>
</template>

<script setup>
const config = useRuntimeConfig()
const baseUrl = config.public.NUXT_PUBLIC_API_BASE_URL || 'https://api.ton-domaine.local'

const { formatPrice } = usePrice()

const props = defineProps({
  shipping: {
    type: Object,
    required: true
  }
})
</script>