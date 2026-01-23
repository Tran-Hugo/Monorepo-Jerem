<template>
  <div class="shadow lg:w-1/2 min-h-[20rem] p-6">
    <div class="flex flex-col lg:flex-row lg:justify-between items-center mb-4">
      <h2 class="font-bold mb-2">Méthode d'expédition</h2>
    </div>

    <div v-if="shippingMethods.length > 0" class="flex flex-col gap-4 max-h-90 overflow-y-auto">
      <div
        v-for="shipping in shippingMethods"
        :key="shipping.id"
        class="flex justify-between items-start p-4 rounded hover:shadow-md"
      >
        <label class="flex items-center gap-4 cursor-pointer">
          <input
            type="radio"
            :value="shipping.id"
            v-model="selectedShippingId"
            class="cursor-pointer"
          />
          <ShippingCard :shipping="shipping" />
        </label>
      </div>
    </div>
    <div v-else>
      <p>Aucune méthode d'expédition disponible.</p>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  shippingMethods: { type: Array, default: () => [] }
})

const formStore = useFormStepperStore()

const selectedShippingId = computed({
  get: () => formStore.shippingMethod?.id ?? null,
  set: (id) => {
    formStore.shippingMethod = props.shippingMethods.find(s => s.id === id) || null
  }
})
</script>
