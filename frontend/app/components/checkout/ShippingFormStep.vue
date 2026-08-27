<template>
  <div class="w-full space-y-6">
    <div>
      <h2 class="text-lg font-bold text-neutral-900">
        Méthode d'expédition
      </h2>
      <p class="text-xs text-neutral-500">
        Choisissez l'option de transport adaptée à vos besoins
      </p>
    </div>

    <div v-if="shippingMethods.length > 0" class="flex flex-col gap-3">
      <div
        v-for="shipping in shippingMethods"
        :key="shipping.id"
        @click="selectedShippingId = shipping.id"
        :class="[
          'relative p-4 sm:p-5 rounded-xl transition-all cursor-pointer flex items-center gap-4',
          selectedShippingId === shipping.id
            ? 'border-2 border-neutral-900 bg-neutral-50/80 shadow-xs ring-2 ring-neutral-900/5'
            : 'border border-neutral-200/90 hover:border-neutral-300 hover:bg-neutral-50/40 bg-white'
        ]"
      >
        <!-- Radio Circle -->
        <div class="pt-0.5">
          <div
            :class="[
              'w-5 h-5 rounded-full border flex items-center justify-center transition-colors',
              selectedShippingId === shipping.id
                ? 'border-neutral-900 bg-neutral-900 text-white'
                : 'border-neutral-300 bg-white'
            ]"
          >
            <div v-if="selectedShippingId === shipping.id" class="w-2 h-2 rounded-full bg-white"></div>
          </div>
        </div>

        <!-- Carte transporteur -->
        <div class="flex-1">
          <ShippingCard :shipping="shipping" />
        </div>
      </div>
    </div>

    <div v-else class="p-8 text-center bg-neutral-50 rounded-xl border border-dashed border-neutral-300">
      <p class="text-sm text-neutral-500">Aucune méthode d'expédition disponible pour le moment.</p>
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
