<template>
  <div class="address">
    <p><strong>{{ address.firstname }} {{ address.lastname }}</strong></p>
    <p>{{ address.street }}</p>
    <p>{{ address.postalCode }} {{ address.city }}</p>
    <p>{{ address.country.name }}</p>
    <p>{{ address.phone }}</p>
  </div>
</template>

<script setup>
defineProps({
  address: Object,
})
</script>
