<template>
  <UCard class="max-w-2xl mx-auto mt-10">
    <template #header>
      <h2 class="text-xl font-bold text-gray-800">Mon Panier</h2>
    </template>

    <div v-if="cart?.products?.length">
      <ul class="divide-y divide-gray-200">
        <li
          v-for="(cartItem, index) in cart.products"
          :key="index"
          class="flex items-center justify-between py-3"
        >
          <!-- Infos produit -->
          <div class="flex items-center gap-3">
            <img
              :src="baseUrl + '/' + cartItem.mainImage.path"
              alt="Image produit"
              class="w-17 h-17 object-cover rounded-lg"
            />
            <div>
              <p class="font-medium">{{ cartItem.product.title }}</p>
              <p class="text-sm text-gray-500">{{ formatPrice(cartItem.product.price) }}</p>
            </div>
          </div>

          <!-- Contrôles quantité -->
          <div class="flex items-center gap-2 border-2 border-yellow-400 rounded-full px-3 py-1 w-[90px] justify-center">
            <div class="flex items-center gap-3">
            <button
              @click="cartItem.quantity === 1 ? removeItem(cartItem.id) : decreaseQuantity(cartItem)"
              class="cursor-pointer flex items-center justify-center"
            >
              <UIcon
                name="i-heroicons-trash"
                v-if="cartItem.quantity === 1"
                class="w-5 h-5 text-gray-700"
              />
              <UIcon
                v-else
                name="i-heroicons-minus"
                class="w-5 h-5 text-gray-700"
              />
            </button>
          

            <span class="text-gray-700 font-medium">{{ cartItem.quantity }}</span>

            <button
              @click="increaseQuantity(cartItem)"
              class="cursor-pointer flex items-center justify-center"
            >
              <UIcon
                name="i-heroicons-plus"
                class="w-5 h-5 text-gray-700"
              />
            </button>
            </div>
          </div>
        </li>
      </ul>

      <!-- Total -->
      <div class="flex justify-between items-center mt-6 pt-4 border-t">
        <p class="text-lg font-semibold">Total :</p>
        <p class="text-lg font-bold text-blue-600">{{ formatPrice(cart.total) }}</p>
      </div>

      <!-- Valider -->
      <NuxtLink to="/checkout">
        <UButton
          color="primary"
          class="w-full mt-6 cursor-pointer font-bold flex justify-center"
        >
          Valider ma commande
        </UButton>
      </NuxtLink>
    </div>

    <div v-else class="text-center py-10 text-gray-500">
      Ton panier est vide 🛍️
    </div>
  </UCard>
</template>

<script setup>
import { useCart } from '@/composables/useCart'
import CartService from '~/services/CartApi'

const config = useRuntimeConfig()
const baseUrl = config.public.NUXT_PUBLIC_API_BASE_URL || 'https://localhost:8000'

const ui = useUiStore()
ui.showLoader()
const { data: cart, error, refresh } = await useCart()
const cartStore = useCartStore()

if (error.value) {
  console.error('Erreur lors de la récupération du panier:', error.value)
}
ui.hideLoader()

const { $apiFetch } = useNuxtApp()
const cartService = CartService($apiFetch);

const { formatPrice } = usePrice();

const increaseQuantity = async (cartItem) => {
  ui.showLoader()
  await cartService.updateQuantity(cartItem.id, cartItem.quantity + 1);
  await refresh()
  cartStore.fetchCount()
  ui.hideLoader()
}

const decreaseQuantity = async (cartItem) => {
  if (cartItem.quantity > 1) {
    ui.showLoader()
    await cartService.updateQuantity(cartItem.id, cartItem.quantity - 1);
    await refresh()
    cartStore.fetchCount()
    ui.hideLoader()
  }
}

const removeItem = async (itemId) => {
  ui.showLoader()
  await cartService.removeProduct(itemId);
  await refresh()
  cartStore.fetchCount()
  ui.hideLoader()
}
</script>
