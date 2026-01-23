<template>
  <form @submit.prevent="onSubmit" class="bg-white shadow-md rounded-lg p-6 space-y-6 max-w-md mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">
      {{ isUpdate ? 'Modifier la catégorie' : 'Créer une catégorie' }}
    </h2>

    <!-- ID (toujours désactivé) -->
    <div>
      <label class="block text-gray-700 font-semibold mb-1">ID</label>
      <input 
        type="text" 
        v-model="form.id" 
        disabled
        class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 text-gray-500 cursor-not-allowed"
        placeholder="ID généré automatiquement"
      />
    </div>

    <!-- Nom -->
    <div>
      <label class="block text-gray-700 font-semibold mb-1">Nom</label>
      <input 
        type="text" 
        v-model="form.name" 
        required
        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
        placeholder="Entrez le nom de la catégorie"
      />
    </div>

    <!-- Bouton -->
    <div class="text-right">
      <nuxt-link 
        to="/admin/categories" 
        class="inline-block mr-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-4 py-2 rounded shadow cursor-pointer"
        >Retour</nuxt-link>
      <button 
        type="submit" 
        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded shadow cursor-pointer"
      >
        {{ isUpdate ? 'Mettre à jour' : 'Créer' }}
      </button>
    </div>
  </form>
</template>

<script setup>
import { reactive, computed } from 'vue'

// Props pour pré-remplir le formulaire (update)
const props = defineProps({
  initialData: {
    type: Object,
    default: () => ({ id: null, name: '' })
  }
})

const emit = defineEmits(['submit'])

// Formulaire réactif
const form = reactive({
  id: props.initialData.id,
  name: props.initialData.name
})

// Détermine si c'est un update
const isUpdate = computed(() => form.id !== null)

const onSubmit = () => {
  emit('submit', { ...form })
}
</script>
