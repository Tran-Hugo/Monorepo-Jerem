<script setup>
import { ref, provide, watch } from 'vue'

const props = defineProps({
  modelValue: {
    type: Number,
    default: 0
  },
  nextLabel: {
    type: String,
    default: 'Suivant'
  },
  prevLabel: {
    type: String,
    default: 'Précédent'
  }
})

const emit = defineEmits(['update:modelValue'])

const currentStep = ref(props.modelValue)
// steps stocke { index, validateFn }
const steps = ref([])

watch(currentStep, (value) => emit('update:modelValue', value))
watch(() => props.modelValue, (value) => {
  if (value !== currentStep.value) currentStep.value = value
})

// Chaque step fournit sa fonction de validation
function registerStep(validateFn = () => true) {
  const index = steps.value.length
  steps.value.push({ index, validateFn })
  return index
}

function next() {
  const current = steps.value[currentStep.value]
  if (current && current.validateFn()) {
    if (currentStep.value < steps.value.length - 1) currentStep.value++
  }
}

function prev() {
  if (currentStep.value > 0) currentStep.value--
}

provide('stepper', {
  currentStep,
  registerStep,
  steps
})
</script>

<template>
  <div>
    <slot></slot>

    <div class="mt-8 pt-6 border-t border-neutral-200/80 flex items-center justify-between gap-4">
      <button
        type="button"
        @click="prev"
        :disabled="currentStep === 0"
        :class="[
          'inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-neutral-300 text-neutral-700 font-medium text-sm hover:bg-neutral-50 hover:border-neutral-400 transition-all cursor-pointer shadow-2xs',
          currentStep === 0 ? 'invisible pointer-events-none' : ''
        ]"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        <span>{{ prevLabel }}</span>
      </button>

      <button
        type="button"
        @click="next"
        :disabled="currentStep === steps.length - 1"
        :class="[
          'inline-flex items-center gap-2 px-7 py-3 rounded-xl bg-neutral-900 hover:bg-neutral-800 text-white font-semibold text-sm transition-all cursor-pointer shadow-sm hover:shadow active:scale-[0.99]',
          currentStep === steps.length - 1 ? 'invisible pointer-events-none' : ''
        ]"
      >
        <span>{{ currentStep === 0 ? 'Continuer vers la livraison' : currentStep === 1 ? 'Procéder au paiement' : nextLabel }}</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </button>
    </div>
  </div>
</template>
