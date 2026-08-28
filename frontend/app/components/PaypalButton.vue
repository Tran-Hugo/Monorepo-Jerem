<script setup>
import { loadScript } from '@paypal/paypal-js'

const { $apiFetch } = useNuxtApp()
const router = useRouter()
const ui = useUiStore()
const cartStore = useCartStore()
const { refresh: refreshCart } = await useCart()
const formStore = useFormStepperStore()

const paypalContainer = ref(null)
const config = useRuntimeConfig()

function formValid() {
  return formStore.address && formStore.billingAddress && formStore.shippingMethod
}

async function pollOrderStatus(orderId) {
  const maxAttempts = 20
  let attempts = 0

  while (attempts < maxAttempts) {
    attempts++
    try {
      const res = await $apiFetch(`/api/orders/${orderId}`)
      if (res.status === 'paid') {
        return res
      }
    } catch (err) {
      console.warn('Polling order failed, retrying...', err)
    }
    await new Promise(r => setTimeout(r, 1000))
  }
  return null
}

onMounted(async () => {
  if (!formValid()) {
    console.error('Formulaire incomplet')
    return
  }

  ui.showLoader()

  const paypal = await loadScript({
    clientId: config.public.paypalClientId,
    currency: 'EUR',
  })

  if (!paypal) {
    ui.hideLoader()
    console.error('Échec du chargement PayPal')
    return
  }
  ui.hideLoader()

  const body = {
    method: 'paypal',
    address: formStore.address,
    billingAddress: formStore.billingAddress,
    shippingMethod: formStore.shippingMethod,
  }

  const actualOrderId = ref(null)

  paypal.Buttons({
    createOrder: async () => {
      const { id, orderId } = await $apiFetch('/api/payment/create', {
        method: 'POST',
        body
      })
      actualOrderId.value = orderId
      return id
    },

    onApprove: async (data) => {
      try {
        await $apiFetch('/api/payment/paypal/capture', {
          method: 'POST',
          body: {
            paypalOrderId: data.orderID,
            orderId: actualOrderId.value
          }
        })
        
        ui.showLoader()
        const res = await pollOrderStatus(actualOrderId.value)
        ui.hideLoader()

        if (res) {
          refreshCart(res.cart)
          cartStore.fetchCount()
          router.push('/success')
        } else {
          const toast = useToast()
          toast.add({
            title: 'Paiement en cours',
            description: 'Votre paiement est en cours de traitement, vous recevrez une confirmation par email sous peu. Si le problème persiste, veuillez contacter le support.',
            color: 'warning',
            duration: 10000,
            icon: 'ic:baseline-error'
          })
          console.warn('Order not paid after polling, please contact support.')
        }

      } catch (err) {
        ui.hideLoader()
        console.error('Erreur capture PayPal :', err)
      }
    },

    onError: err => {
      ui.hideLoader()
      console.error('Erreur PayPal :', err)
    }

  }).render(paypalContainer.value)
})
</script>

<template>
  <div class="w-full space-y-6">
    <div class="text-center max-w-md mx-auto space-y-2">
      <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto shadow-2xs">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
        </svg>
      </div>
      <h2 class="text-lg font-bold text-neutral-900">
        Paiement sécurisé
      </h2>
      <p class="text-xs text-neutral-500 leading-relaxed">
        Réglez vos achats en toute confiance avec votre compte PayPal ou par Carte Bancaire sans frais supplémentaires.
      </p>
    </div>

    <!-- Conteneur bouton PayPal officiel -->
    <div class="flex justify-center py-2">
      <div ref="paypalContainer" class="w-full max-w-md min-h-[120px] flex justify-center" />
    </div>

    <!-- Badges de réassurance -->
    <div class="pt-6 border-t border-neutral-200/80 grid grid-cols-1 sm:grid-cols-3 gap-3 text-center">
      <div class="flex sm:flex-col items-center justify-center gap-2 p-2.5 rounded-lg bg-neutral-50 border border-neutral-200/60">
        <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
        </svg>
        <span class="text-[11px] font-medium text-neutral-700">Chiffrement SSL 256-bit</span>
      </div>

      <div class="flex sm:flex-col items-center justify-center gap-2 p-2.5 rounded-lg bg-neutral-50 border border-neutral-200/60">
        <svg class="w-4 h-4 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 004 11a7.96 7.96 0 001.07 3.99" />
        </svg>
        <span class="text-[11px] font-medium text-neutral-700">Protection des achats</span>
      </div>

      <div class="flex sm:flex-col items-center justify-center gap-2 p-2.5 rounded-lg bg-neutral-50 border border-neutral-200/60">
        <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
        </svg>
        <span class="text-[11px] font-medium text-neutral-700">Confirmation instantanée</span>
      </div>
    </div>
  </div>
</template>
