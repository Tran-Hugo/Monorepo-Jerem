<template>
    <div
      class="flex gap-6 items-start p-4 rounded"
    >
        <img :src="`${baseUrl}/${shipping.image.path}`" :alt="shipping.image.altTxt" class="w-20 h-20 object-contain rounded shadow">
        <div>
            <h3 class="font-semibold">{{ shipping.name }}</h3>
            <p v-if="shipping.description">{{ shipping.description }}.</p>
            <p>Prix : {{ formatPrice(shipping.price) }}</p>
        </div>
    </div>
</template>

<script setup>
const config = useRuntimeConfig()
const baseUrl = config.public.NUXT_PUBLIC_API_BASE_URL || 'https://localhost:8000'

const { formatPrice } = usePrice();

const props = defineProps({
    shipping:{
        type: Object,
        required:true
    }
})
</script>