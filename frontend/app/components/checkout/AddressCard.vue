<template>
  <div class="space-y-1">
    <div class="flex items-center gap-2">
      <h3 class="font-bold text-neutral-900 text-sm sm:text-base">
        {{ address.firstname }} {{ address.lastname }}
      </h3>
      <span v-if="address.company" class="text-xs px-2 py-0.5 rounded bg-neutral-200/70 text-neutral-700 font-medium">
        {{ address.company }}
      </span>
    </div>
    <p class="text-xs sm:text-sm text-neutral-600 leading-relaxed">
      {{ formattedAddress }}
    </p>
    <div v-if="address.phone" class="inline-flex items-center gap-1.5 text-xs text-neutral-500 pt-0.5">
      <svg class="w-3.5 h-3.5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
      </svg>
      <span>{{ address.phone }}</span>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  address: {
    type: Object,
    required: true
  }
})

const formattedAddress = computed(() => {
  const parts = [
    props.address.street,
    props.address.postalCode,
    props.address.city,
    props.address.country?.name
  ].filter(Boolean)

  return parts.join(', ')
})
</script>