<template>
  <div class="bg-white border border-neutral-200/80 rounded-2xl p-6 shadow-xs space-y-6">
    <div class="flex items-center justify-between">
      <h2 class="font-bold text-lg text-neutral-900">
        Récapitulatif
      </h2>
      <NuxtLink to="/cart" class="text-xs font-semibold text-neutral-500 hover:text-neutral-900 transition-colors">
        Modifier
      </NuxtLink>
    </div>

    <!-- Liste des articles du panier -->
    <div v-if="cart?.products?.length" class="space-y-3 max-h-60 overflow-y-auto pr-1">
      <div
        v-for="(item, index) in cart.products"
        :key="index"
        class="flex items-center justify-between gap-3 text-xs"
      >
        <div class="flex items-center gap-3 min-w-0">
          <div class="w-11 h-11 rounded-lg bg-neutral-100 border border-neutral-200/60 overflow-hidden flex-shrink-0 flex items-center justify-center">
            <img
              v-if="item?.mainImage?.path"
              :src="`${baseUrl}/${item.mainImage.path}`"
              :alt="item.product.title"
              class="w-full h-full object-cover"
            />
            <svg v-else class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
          </div>

          <div class="min-w-0">
            <p class="font-medium text-neutral-800 truncate">
              {{ item.product.title }}
            </p>
            <p class="text-[11px] text-neutral-400">
              Qté : {{ item.quantity }}
            </p>
          </div>
        </div>

        <span class="font-semibold text-neutral-900 flex-shrink-0">
          {{ formatPrice(item.product.price * item.quantity) }}
        </span>
      </div>
    </div>

    <!-- Calcul des montants -->
    <div class="space-y-2.5 pt-4 border-t border-neutral-100 text-sm">
      <div class="flex justify-between text-neutral-600">
        <span>Sous-total articles</span>
        <span class="font-medium text-neutral-900">{{ formatPrice(articleTotal) }}</span>
      </div>

      <div class="flex justify-between text-neutral-600">
        <span>Frais de livraison</span>
        <span v-if="formStore.shippingMethod" class="font-medium text-neutral-900">
          {{ formatPrice(formStore.shippingMethod.price) }}
        </span>
        <span v-else class="text-xs text-neutral-400 italic">
          Calculé à l'étape suivante
        </span>
      </div>
    </div>

    <div class="pt-4 border-t border-neutral-200">
      <div class="flex justify-between items-baseline mb-1">
        <span class="text-base font-bold text-neutral-900">Total à payer</span>
        <span class="text-2xl font-black text-neutral-950">
          {{ formatPrice(total) }}
        </span>
      </div>
      <p class="text-[11px] text-neutral-400 text-right">Taxes incluses</p>
    </div>

    <NuxtLink to="/cart" class="block">
      <button
        type="button"
        class="w-full py-2.5 px-4 rounded-xl border border-neutral-200 hover:border-neutral-300 hover:bg-neutral-50 text-neutral-700 font-medium text-xs transition-colors cursor-pointer text-center"
      >
        Modifier le panier
      </button>
    </NuxtLink>

    <!-- Micro-réassurance -->
    <div class="pt-4 border-t border-neutral-100 space-y-2 text-[11px] text-neutral-500">
      <div class="flex items-center gap-2">
        <svg class="w-3.5 h-3.5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span>Paiement chiffré et sécurisé</span>
      </div>
      <div class="flex items-center gap-2">
        <svg class="w-3.5 h-3.5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span>Expédition rapide & soignée</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({ 
  articleTotal: { type: Number, default: 0 },
  cart: { type: Object, default: () => null }
})

const formStore = useFormStepperStore()
const { formatPrice } = usePrice()

const config = useRuntimeConfig()
const baseUrl = config.public.NUXT_PUBLIC_API_BASE_URL || 'https://api.ton-domaine.local'

const total = computed(() => props.articleTotal + (formStore.shippingMethod?.price ?? 0))
</script>