<template>
  <div>
    <component
      v-if="!loading"
      :is="currentForm"
      ref="formRef"
      :initial-data="entityData"
      @submit="submitEntity"
    />

    <div v-else>
      <p>Chargement des données...</p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { formsMap, composablesMap, servicesMap } from '@/utils/adminMaps'

const route = useRoute()
const router = useRouter()
const ui = useUiStore()
const entity = route.params.entity
const id = route.params.id || null

const isUpdate = computed(() => !!id)
console.log(`Is Update: ${isUpdate.value}`);


const formRef = ref(null)
const entityData = ref(null)
const loading = ref(isUpdate.value) // si update, on charge les données

// Composant formulaire actuel
const currentForm = computed(() => {
  const form = formsMap[entity]
  if (!form) throw new Error(`No form found for entity: ${entity}`)
  return form
})

// Composable pour récupérer les données si update
const composable = composablesMap[entity]
if (isUpdate.value && !composable) throw new Error(`No composable found for entity: ${entity}`)

// Service pour create/update
const { $apiFetch } = useNuxtApp()
const currentService = computed(() => {
  const service = servicesMap[entity]
  if (!service) throw new Error(`No service found for entity: ${entity}`)
  return service($apiFetch)
})

// Récupérer les données existantes pour edit
if (isUpdate.value) {
  const { data, error } = await composable(id)
  entityData.value = data.value
  loading.value = false
  if (error.value) console.error(error.value)
} else {
  entityData.value = { id: null, name: '' } // Formulaire vide pour création
  loading.value = false
}

// Fonction pour créer ou mettre à jour
const submitEntity = async (payload) => {
  try {
    ui.showLoader();
    if (isUpdate.value) {
      await currentService.value.update(id, payload)
    } else {
      await currentService.value.create(payload)
    }
    ui.hideLoader();
    router.push(`/admin/${entity}`)
  } catch (e) {
    ui.hideLoader();
    console.error(e)
    alert('Erreur lors de l’opération.')
  }
}
</script>
