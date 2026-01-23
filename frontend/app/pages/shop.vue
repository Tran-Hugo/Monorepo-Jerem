<template>
  <div class="w-full min-h-[90vh] md:px-4 flex justify-center items-center my-bg">
    <div class="w-full md:w-5/6 min-h-180 bg-white/90 rounded-lg p-8 shadow-lg my-8 space-y-12">

      <!-- NOUVEAUTÉS -->
      <section>
        <div class="flex justify-between items-center mb-4">
          <h1 class="text-3xl font-bold md:w-5/6">Nouveautés</h1>
          <NuxtLink to="/result?sort=desc">
            <p class="font-semibold hover:underline cursor-pointer">voir plus</p>
          </NuxtLink>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
          <ProductCard
            v-for="product in shopShowcaseData.latest"
            :key="product.id"
            :product="product"
          />
        </div>
      </section>

      <!-- ARTS -->
      <section>
        <div class="flex justify-between items-center mb-4">
          <h1 class="text-3xl font-bold md:w-5/6">Arts</h1>
          <NuxtLink to="/result?category=art">
            <p class="font-semibold hover:underline cursor-pointer">voir plus</p>
          </NuxtLink>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
          <ProductCard
            v-for="product in shopShowcaseData.art"
            :key="product.id"
            :product="product"
          />
        </div>
      </section>

      <!-- PHOTOGRAPHIES -->
      <section>
        <div class="flex justify-between items-center mb-4">
          <h1 class="text-3xl font-bold md:w-5/6">Photographies</h1>
          <NuxtLink to="/result?category=photo">
            <p class="font-semibold hover:underline cursor-pointer">voir plus</p>
          </NuxtLink>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
          <ProductCard
            v-for="product in shopShowcaseData.photo"
            :key="product.id"
            :product="product"
          />
        </div>
      </section>

    </div>
  </div>
</template>

<script setup>
const ui = useUiStore()

ui.showLoader()

const { data: shopShowcaseData, error } = await useShopShowcase()

if (error.value) {
  console.error(
    'Erreur lors de la récupération du showcase du magasin :',
    error.value
  )
}

ui.hideLoader()
</script>

<style scoped>
.my-bg {
  background-image: url('/background.png');
}
</style>