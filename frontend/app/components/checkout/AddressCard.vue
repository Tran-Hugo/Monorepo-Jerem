<template>
    <div>
        <h3 class="font-semibold">{{ address.firstname }} {{ address.lastname }}</h3>
        <p>{{ formattedAddress }}.</p>
        <p v-if="address.phone">Numéro de téléphone : {{ address.phone }}</p>
    </div>
</template>

<script setup>
const props = defineProps({
    address: {
        type: Object,
        required: true
    }
})

console.log(props.address);


const formattedAddress = computed(() => {
  const parts = [
    props.address.street,
    props.address.city,
    props.address.postalCode,
    props.address.country.name
  ].filter(Boolean)

  return parts.join(', ')
})
</script>