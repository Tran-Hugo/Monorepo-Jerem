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

    <div class="mt-6 flex justify-between">
      <button
        @click="prev"
        :disabled="currentStep === 0"
        :class="[
          'rounded px-4 py-2 bg-gray-300 text-gray-700 disabled:opacity-50 cursor-pointer',
          currentStep === 0 ? 'invisible' : ''
        ]"
      >
        {{ prevLabel }}
      </button>

      <button
        @click="next"
        :disabled="currentStep === steps.length - 1"
        :class="[
          'rounded px-4 py-2 bg-blue-500 text-white disabled:opacity-50 cursor-pointer',
          currentStep === steps.length - 1 ? 'invisible' : ''
        ]"
      >
        {{ nextLabel }}
      </button>
    </div>
  </div>
</template>
