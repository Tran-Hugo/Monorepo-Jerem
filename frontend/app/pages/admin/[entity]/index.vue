<template>
  <div class="ag-theme-alpine">
    <div class="flex justify-between items-center mb-4 mx-4">
      <NuxtLink to="/admin" class="text-blue-500 hover:underline">Retour à l'administration</NuxtLink>
    <h1 class="text-2xl font-bold mb-4">Gestion des {{ entity }}</h1>
    <div class="flex justify-between mb-4">
      <NuxtLink
        v-if="!hideAddButtonEntities.includes(entity)"
        :to="`/admin/${entity}/form`"
        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
      >
        Ajouter
      </NuxtLink>
    </div>
    </div>
    <ClientOnly>
      <AgGridVue
        v-if="rowData.length > 0"
        :rowData="rowData"
        :columnDefs="colDefs"
        style="height: 700px"
        domLayout="normal"
        :context="context"
        :pagination="true"
        :defaultColDef="defaultColDef"
      />
      <div v-else>
        <p>Chargement des données...</p>
      </div>
    </ClientOnly>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { columnsMap } from '@/utils/colDefs'
import { collectionsComposablesMap, servicesMap } from '@/utils/adminMaps'

definePageMeta({ layout: 'admin' })

const router = useRouter()
const route = useRoute()
const entity = route.params.entity

const defaultColDef = {
  filter: true,
  floatingFilter: true,
}

const ui = useUiStore()

// Composable correspondant à l'entité
const fetchData = collectionsComposablesMap[entity]
if (!fetchData) throw new Error(`No composable found for entity: ${entity}`)

ui.showLoader()
// ⚡ Await pour SSR : on a les données au rendu
const { data, error } = await fetchData()

if (error?.value) {
  console.error(`Erreur lors de la récupération des données pour ${entity}:`, error.value)
}
ui.hideLoader()
// Gestion générique des données : on prend "member" si présent, sinon la réponse directe
const datas = computed(() => {
  if (!data.value) return []
  if (Array.isArray(data.value.member)) return data.value.member
  if (Array.isArray(data.value)) return data.value
  return [data.value]
})

// Données pour AgGrid
const rowData = datas

// Colonnes dynamiques selon l’entité
const colDefs = ref(columnsMap[entity] || [])

// Service correspondant à l'entité pour les actions (delete, etc.)
const { $apiFetch } = useNuxtApp()
const currentService = computed(() => {
  const service = servicesMap[entity]
  if (!service) return null // Pas de service pour cette entité
  return service($apiFetch)
})
const config = useRuntimeConfig()
const baseUrl = config.public.NUXT_PUBLIC_API_BASE_URL || 'https://localhost:8000'
const context = {
  router,
  currentService,
  baseUrl
}

const hideAddButtonEntities = ['commandes']
</script>

<style>
.ag-paging-panel {
  height: auto;
  flex-wrap: wrap-reverse;
  row-gap: 1rem;
  padding: .25rem;
}
</style>