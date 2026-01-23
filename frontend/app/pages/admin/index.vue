<template>
  <div class="bg-white shadow rounded-lg p-6">
    <h1 class="text-2xl font-bold mb-4">Bienvenue dans le panneau d'administration</h1>
    <p>Choisissez une section dans le menu à gauche pour commencer.</p>
  </div>
  <section class="bg-white shadow rounded-lg p-6">
      <h2 class="text-xl font-semibold mb-4">Statistiques rapides</h2>
      <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <div class="bg-blue-100 p-4 rounded-lg text-center">
          <p class="text-sm">Total produits</p>
          <p class="text-2xl font-bold">{{ dashboardData?.products }}</p>
        </div>
        <div class="bg-green-100 p-4 rounded-lg text-center">
          <p class="text-sm">Total commandes expédiées</p>
          <p class="text-2xl font-bold">{{ dashboardData?.shippedOrders }}</p>
        </div>
        <div class="bg-yellow-100 p-4 rounded-lg text-center">
          <p class="text-sm">Commandes en attente d'expédition</p>
          <p class="text-2xl font-bold">{{ dashboardData?.paidOrders }}</p>
        </div>
      </div>
    </section>
</template>

<script setup>
definePageMeta({
  layout: 'admin'
})
const ui = useUiStore()
ui.showLoader()
const { data: dashboardData, error } = await useDashboard();
if (error.value) {
  console.error("Erreur lors de la récupération des statistiques du tableau de bord :", error.value)
}
ui.hideLoader()
</script>
