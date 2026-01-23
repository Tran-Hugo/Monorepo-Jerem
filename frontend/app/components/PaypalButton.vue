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
        await $apiFetch('/api/payment/webhooks?type=paypal&action=capture', {
          method: 'POST',
          body: { orderID: data.orderID }
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
  <div ref="paypalContainer" class="w-[30rem]" />
</template>
