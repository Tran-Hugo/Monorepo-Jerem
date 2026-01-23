<template>
  <div class="order">

    <!-- HEADER -->
    <section class="order-header">
      <h2>Commande #{{ order.id }}</h2>

      <span
        class="status"
        :class="statusMap[order.status]?.class ?? 'bg-gray-500'"
      >
        {{ statusMap[order.status]?.text ?? order.status }}
      </span>

      <p class="date">
        {{ formatDate(order.createdAt) }}
      </p>
    </section>

    <!-- USER -->
    <section class="block">
      <h3>Client</h3>
      <p>{{ order.user.firstname }} {{ order.user.lastname }}</p>
      <p>{{ order.user.email }}</p>
    </section>

    <!-- ADDRESSES -->
    <section class="addresses">
      <div class="block">
        <h3>Adresse de livraison</h3>
        <AddressRead :address="order.shippingAddress" />
      </div>

      <div class="block">
        <h3>Adresse de facturation</h3>
        <AddressRead :address="order.billingAddress" />
      </div>
    </section>

    <!-- SHIPPING -->
    <section class="block">
      <h3>Mode de livraison</h3>
      <div class="shipping">
        <img
          v-if="order.shippingMethod.image"
          :src="imageUrl(order.shippingMethod.image.path)"
          :alt="order.shippingMethod.image.altText"
        />
        <span>{{ order.shippingMethod.name }}</span>
        <strong>{{ formatPrice(order.shippingPriceAtPurchase) }}</strong>
      </div>
    </section>

    <!-- ITEMS -->
    <section class="block">
      <h3>Articles</h3>

      <div
        v-for="item in order.orderItems"
        :key="item.id"
        class="item"
      >
        <img
          :src="imageUrl(mainImage(item.product.images))"
          class="h-[5rem] object-contain"
          alt=""
        />

        <div class="item-info">
          <strong>{{ item.product.title }}</strong>
          <p>Quantité : {{ item.quantity }}</p>
        </div>

        <div class="item-price">
          {{ formatPrice(item.priceAtPurchase) }}
        </div>
      </div>
    </section>

    <!-- TOTAL -->
    <section class="total">
      <span>Total</span>
      <strong>{{ formatPrice(order.total) }}</strong>
    </section>

  </div>
</template>

<script setup>
defineProps({
  order: {
    type: Object,
    required: true,
  },
})

/* 🔹 Mapping status (identique à ag-Grid) */
const statusMap = {
  pending: {
    text: 'En attente',
    class: 'bg-yellow-500',
  },
  shipped: {
    text: 'Expédiée',
    class: 'bg-green-500',
  },
  paid: {
    text: "Payée en attente d'expédition",
    class: 'bg-blue-500',
  },
  cancelled: {
    text: 'Annulée',
    class: 'bg-red-500',
  },
}

const formatPrice = (cents) => (cents / 100).toFixed(2).replace('.', ',') + ' €'

const formatDate = (date) => new Date(date).toLocaleString('fr-FR')

const config = useRuntimeConfig()
const baseUrl = config.public.NUXT_PUBLIC_API_BASE_URL || 'https://localhost:8000'

const imageUrl = (path) => path ? `${baseUrl}/${path}` : ''

const mainImage = (images) => images.find(img => img.main)?.path ?? images[0]?.path
</script>

<style scoped>
.order {
  max-width: 900px;
  margin: auto;
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.order-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.status {
  color: white;
  padding: 4px 12px;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 600;
  white-space: nowrap;
}

.block {
  border: 1px solid #e5e7eb;
  padding: 1rem;
  border-radius: 8px;
}

.addresses {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.shipping {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.shipping img {
  height: 40px;
}

.item {
  display: grid;
  grid-template-columns: 80px 1fr auto;
  align-items: center;
  gap: 1rem;
  padding: 0.5rem 0;
}

.item img {
  width: 80px;
  border-radius: 6px;
}

.total {
  display: flex;
  justify-content: space-between;
  font-size: 1.3rem;
  font-weight: bold;
}
</style>
