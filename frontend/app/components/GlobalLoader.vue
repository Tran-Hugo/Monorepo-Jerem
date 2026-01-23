<!-- components/GlobalLoader.vue -->
<template>
  <Teleport to="body">
    <div v-if="ui.loading" class="fixed inset-0 flex items-center justify-center bg-gray-50/75 z-[999999]">
      <UIcon name="svg-spinners:3-dots-move" class="size-16" />
    </div>
  </Teleport>
</template>

<script setup>
import { useUiStore } from '~/stores/useUiStore'
const ui = useUiStore()
</script>
