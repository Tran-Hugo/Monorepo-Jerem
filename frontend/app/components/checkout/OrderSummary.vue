<template>
  <div class="bg-white p-6">
    <h2 class="font-bold text-lg mb-4">Résumé de la commande</h2>
    <div class="flex flex-col gap-2">
      <div class="flex justify-between">
        <span>Articles :</span>
        <span>{{ formatPrice(articleTotal) }}</span>
      </div>
      <div class="flex justify-between">
        <span>Livraison :</span>
        <span>{{ formStore.shippingMethod?.price ? formatPrice(formStore.shippingMethod.price) : "-,-- €" }}</span>
      </div>
    </div>
    <hr class="my-4 border-gray-300" />
    <div class="flex justify-between font-bold text-lg">
      <span>Total :</span>
      <span>{{ formatPrice(total) }}</span>
    </div>
    <NuxtLink to="/cart">
        <button
        class="mt-6 w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-700 transition cursor-pointer"
        >
        Modifier le panier
        </button>
    </NuxtLink>
  </div>
</template>

<script setup>
const props = defineProps({ 
    articleTotal: { type: Number, default: 0 }
})

const formStore = useFormStepperStore()

const { formatPrice } = usePrice();

const total = computed(() => props.articleTotal + (formStore.shippingMethod?.price ?? 0 ))

</script>