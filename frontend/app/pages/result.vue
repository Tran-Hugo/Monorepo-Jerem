<template>
  <div class="w-full min-h-[90vh] md:px-4 flex justify-center items-center my-bg">
    <div class="w-full md:w-5/6 min-h-180 bg-white/90 rounded-lg p-8 shadow-lg my-8 space-y-12">

      <!-- HEADER -->
      <section>
        <div class="flex justify-between items-center mb-4">
          <h1 class="text-3xl font-bold md:w-5/6">
            Résultats de recherche
          </h1>
        </div>

        <p class="text-gray-600 mb-6">
          Recherche : <strong>{{ q || '—' }}</strong>
          <span v-if="category"> | Catégorie : <strong>{{ category }}</strong></span>
        </p>
      </section>


      <!-- RÉSULTATS -->
      <section>
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-2xl font-semibold md:w-5/6">
            Produits trouvés ({{ products.length }})
          </h2>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
            <ProductCard
                v-for="product in products"
                :key="product.id"
                :product="product"
            />
        </div>
      </section>

    </div>
  </div>
</template>

<script setup>
const route = useRoute()
const router = useRouter()

// Query params
const q = computed(() => route.query.q ?? '')
const sort = computed(() => route.query.sort ?? null)
const category = computed(() => route.query.category ?? null)
const page = computed(() => route.query.page ?? 1)
const limit = computed(() => route.query.limit ?? 20)

const ui = useUiStore()
const toast = useToast()

ui.showLoader()

const { data: products, error, refresh } = await useSearchProducts(
  q.value,
  sort.value,
  category.value,
  page.value,
  limit.value
)

if (error.value) {
  ui.hideLoader()
  toast.add({
    title: 'Erreur lors de la récupération des produits',
    description: 'Une erreur est survenue lors de la récupération des produits. Veuillez réessayer plus tard.',
    color: 'warning',
    icon: 'ic:baseline-error'
  })
  console.error("Erreur lors de la récupération des produits :", error.value)
}

ui.hideLoader()

watch(
  () => [q.value, sort.value, category.value, page.value, limit.value],
  async () => {
    ui.showLoader()
    try {
      await refresh()
    } catch (err) {
      toast.add({
        title: 'Erreur lors de la récupération des produits',
        description: 'Une erreur est survenue lors de la récupération des produits. Veuillez réessayer plus tard.',
        color: 'warning',
        icon: 'ic:baseline-error'
      })
      console.error("Erreur lors de la récupération des produits :", err)
    } finally {
      ui.hideLoader()
    }
  }
)
</script>

<style scoped>
.my-bg {
  background-image: url('/background.png');
}
</style>