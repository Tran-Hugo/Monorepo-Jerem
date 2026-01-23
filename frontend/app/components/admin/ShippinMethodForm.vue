<template>
  <form @submit.prevent="onSubmit" class="bg-white shadow-md rounded-lg p-6 space-y-6 max-w-md mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">
      {{ isUpdate ? 'Modifier la Méthode de livraison' : 'Créer une Méthode de livraison' }}
    </h2>

    <!-- Nom -->
    <div>
      <label class="block text-gray-700 font-semibold mb-1">Nom</label>
      <input 
        type="text" 
        v-model="form.name" 
        required
        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
        placeholder="Entrez le nom"
      />
    </div>

    <!-- Description -->
    <div>
      <label class="block text-gray-700 font-semibold mb-1">Description</label>
      <textarea
        v-model="form.description"
        rows="4"
        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
        placeholder="Entrez la description de la méthode de livraison"
      ></textarea>
    </div>

    <!-- Prix -->
    <div>
      <label class="block text-gray-700 font-semibold mb-1">Prix</label>
      <input 
        type="number"
        step="0.01" 
        v-model.number="priceEuros" 
        required
        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
        placeholder="Entrez le prix"
      />
    </div>

    <!-- Image -->
    <div>
      <label class="block text-gray-700 font-semibold mb-1">Image</label>

      <input
        type="file"
        @change="onFileChange"
        class="w-full border rounded border-gray-300 px-3 py-2"
      />

      <!-- Preview -->
      <div v-if="form.imagePreview" class="relative w-20 h-20 mt-2 border rounded overflow-hidden">
        <img :src="form.imagePreview" :alt="form.image?.altText || 'Image'" class="w-full h-full object-cover" />
        <button type="button" @click="removeImage"
          class="absolute top-0 right-0 bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">&times;</button>
      </div>
    </div>

    <!-- Boutons -->
    <div class="text-right">
      <nuxt-link to="/admin/expeditions" class="inline-block mr-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-4 py-2 rounded shadow cursor-pointer">Retour</nuxt-link>
      <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded shadow cursor-pointer">
        {{ isUpdate ? 'Mettre à jour' : 'Créer' }}
      </button>
    </div>
  </form>
</template>

<script setup>
const config = useRuntimeConfig()
const baseUrl = config.public.NUXT_PUBLIC_API_BASE_URL || 'https://localhost:8000'

const props = defineProps({
  initialData: {
    type: Object,
    default: () => ({
      id: null,
      name: '',
      description:'',
      price: 0,
      image: null
    })
  }
})

const emit = defineEmits(['submit'])

const form = reactive({
  id: props.initialData.id,
  name: props.initialData.name,
  description: props.initialData.description,
  price: props.initialData.price,
  image: props.initialData.image, // image existante
  file: null,                    // nouvelle image
  imagePreview: props.initialData.image ? `${baseUrl}/${props.initialData.image.path}` : null
})

const removedExistingImage = ref(false)

const priceEuros = computed({
  get: () => form.price / 100,
  set: (val) => {
    form.price = Math.round((val || 0) * 100)
  }
})

const isUpdate = computed(() => form.id !== null)

const onFileChange = (event) => {
  const file = event.target.files[0]
  if (!file) return

  // libère l'ancienne preview si existante
  if (form.file?.preview) URL.revokeObjectURL(form.file.preview)

  file.preview = URL.createObjectURL(file)
  form.file = file
  form.imagePreview = file.preview
  removedExistingImage.value = true // on considère que l'ancienne image sera remplacée
}

const removeImage = () => {
  if (form.file?.preview) URL.revokeObjectURL(form.file.preview)
  form.file = null
  form.image = null
  form.imagePreview = null
  removedExistingImage.value = true
}

const onSubmit = () => {
  const payload = new FormData()

  payload.append('name', form.name)
  if (form.description) payload.append('description', form.description)
  payload.append('price', form.price)

  if (form.file) {
    payload.append('file', form.file)
  }

  if (removedExistingImage.value && !form.file) {
    payload.append('removeImage', 'true')
  }

  emit('submit', payload)
}

</script>
