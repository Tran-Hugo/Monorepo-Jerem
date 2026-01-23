<template>
  <div v-if="currentStep === stepIndex">
    <slot></slot>
  </div>
</template>

<script setup>
import { inject, ref, onMounted } from 'vue'

const stepper = inject('stepper')

const props = defineProps({
  // Fonction de validation spécifique au step
  validateStep: {
    type: Function,
    default: () => true
  }
})

const stepIndex = ref(stepper.steps.length)

onMounted(() => {
  // Enregistre le step avec sa fonction de validation
  stepIndex.value = stepper.registerStep(props.validateStep)
})

const currentStep = stepper.currentStep
</script>
