<template>
  <form
    @submit.prevent="onSubmit"
    class="bg-white shadow-md rounded-lg p-6 space-y-6 max-w-7xl mx-auto"
  >
    <h2 class="text-2xl font-bold text-gray-800 mb-4">
      {{ isUpdate ? 'Modifier le produit' : 'Créer un produit' }}
    </h2>

    <!-- ID -->
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

    <!-- Titre -->
    <div>
      <label class="block text-gray-700 font-semibold mb-1">Titre</label>
      <input
        type="text"
        v-model="form.title"
        required
        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
        placeholder="Entrez le titre du produit"
      />
    </div>

    <!-- Description -->
    <div>
      <label class="block text-gray-700 font-semibold mb-1">Description</label>
      <textarea
        v-model="form.description"
        rows="4"
        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
        placeholder="Entrez la description du produit"
      ></textarea>
    </div>

    <!-- Prix -->
    <div>
      <label class="block text-gray-700 font-semibold mb-1">Prix</label>
      <input
        type="number"
        v-model.number="priceEuros"
        required
        step="0.01"
        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
        placeholder="Entrez le prix"
      />
    </div>

    <!-- Catégorie -->
    <div>
      <label class="block text-gray-700 font-semibold mb-1">Catégorie</label>
      <USelect
        v-model="form.categoryId"
        :items="categoriesOptions"
        multiple
        placeholder="Sélectionnez une catégorie"
        color="neutral"
        class="w-full border-gray-300 py-3"
      />
      <div v-if="categoriesError" class="text-red-500 mt-1">
        Impossible de charger les catégories.
      </div>
    </div>

    <!-- Visible -->
    <div class="flex items-center space-x-2">
      <input type="checkbox" v-model="form.visible" id="visible" class="cursor-pointer" />
      <label for="visible" class="text-gray-700 font-semibold">Visible</label>
    </div>

    <!-- Stock -->
    <div>
      <label class="block text-gray-700 font-semibold mb-1">Stock</label>
      <input
        type="number"
        v-model.number="form.stock"
        required
        min="0"
        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
        placeholder="Quantité en stock"
      />
    </div>

    <!-- Position dans la mosaïque -->
    <div>
      <label class="block text-gray-700 font-semibold mb-1">Position dans la mosaïque (Page d'accueil)</label>
      <select
        v-model="form.mosaicPosition"
        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white"
      >
        <option :value="null">-- Non affiché dans la mosaïque --</option>
        <option :value="1">1 - Grande tuile vedette (Haut-Gauche)</option>
        <option :value="2">2 - Petite tuile (Haut-Centre)</option>
        <option :value="3">3 - Petite tuile (Haut-Droite 1)</option>
        <option :value="4">4 - Petite tuile (Haut-Droite 2)</option>
        <option :value="5">5 - Petite tuile (Centre-Milieu)</option>
        <option :value="6">6 - Petite tuile (Bas-Gauche 1)</option>
        <option :value="7">7 - Petite tuile (Bas-Gauche 2)</option>
        <option :value="8">8 - Petite tuile (Bas-Centre)</option>
        <option :value="9">9 - Grande tuile maîtresse (Bas-Droite)</option>
      </select>
      <p class="text-xs text-gray-500 mt-1">
        Les positions 1 et 9 correspondent aux deux grands emplacements 2x2 mis en valeur.
      </p>
    </div>

    <!-- Fichiers -->
    <div>
      <label class="block text-gray-700 font-semibold mb-1">Images</label>
      <input
        type="file"
        @change="onFileChange"
        multiple
        class="w-full border rounded border-gray-300 px-3 py-2"
      />

      <!-- Message info -->
      <p class="text-sm text-gray-500 mt-2 mb-1">
        Cliquez sur une image pour la définir comme image principale du produit.
      </p>

      <!-- Aperçu des images -->
      <div class="flex flex-wrap gap-2">
        <!-- Images existantes -->
        <div
          v-for="img in existingImagePreviews"
          :key="img.id"
          :class="[
            'w-20 h-20 border rounded overflow-hidden relative cursor-pointer',
            img.id === mainImageId ? 'border-blue-500 border-4' : 'border-gray-300'
          ]"
          @click="mainImageId = img.id"
        >
          <img :src="baseUrl + '/' + img.path" alt="Preview" class="w-full h-full object-cover" />
          <button
            type="button"
            @click.stop="removeExistingImage(img.id)"
            class="absolute top-1 right-1 bg-red-500 text-white text-sm rounded-full w-6 h-6 flex items-center justify-center cursor-pointer transition-all duration-200 hover:bg-red-700"
          >
            &times;
          </button>
        </div>

        <!-- Nouvelles images -->
        <div
          v-for="(img, index) in newImagePreviews"
          :key="img.tempId"
          :class="[
            'w-20 h-20 border rounded overflow-hidden relative cursor-pointer',
            img.tempId === mainImageId ? 'border-blue-500 border-4' : 'border-gray-300'
          ]"
          @click="mainImageId = img.tempId"
        >
          <img :src="img.url" alt="Preview" class="w-full h-full object-cover" />
          <button
            type="button"
            @click.stop="removeNewImage(index)"
            class="absolute top-1 right-1 bg-red-500 text-white text-sm rounded-full w-6 h-6 flex items-center justify-center cursor-pointer transition-all duration-200 hover:bg-red-700"
          >
            &times;
          </button>
        </div>
      </div>
    </div>

    <!-- Boutons -->
    <div class="text-right">
      <nuxt-link
        :to="`/admin/produits`"
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
import { reactive, ref, computed } from 'vue'

const config = useRuntimeConfig()
const baseUrl = config.public.NUXT_PUBLIC_API_BASE_URL || 'https://localhost:8000'

let tempImageId = 0

const props = defineProps({
  initialData: {
    type: Object,
    default: () => ({
      id: null,
      title: '',
      description: '',
      price: 0,
      visible: true,
      stock: 0,
      files: [],
      images: [],
      categories: [],
      mainImage: null,
      mosaicPosition: null
    })
  }
})

const emit = defineEmits(['submit'])

const form = reactive({
  id: props.initialData.id,
  title: props.initialData.title,
  description: props.initialData.description,
  price: props.initialData.price,
  visible: props.initialData.visible,
  stock: props.initialData.stock,
  mosaicPosition: props.initialData.mosaicPosition !== undefined ? props.initialData.mosaicPosition : null,
  files: [],
  categoryId: Array.isArray(props.initialData.categories) 
    ? props.initialData.categories.map(cat => cat.id) 
    : []
})

const priceEuros = computed({
  get: () => form.price / 100,
  set: (val) => {
    form.price = Math.round((val || 0) * 100)
  }
})

const isUpdate = computed(() => form.id !== null)

// Categories
const { data: categories, error: categoriesError } = await useCategories()
const categoriesOptions = ref(
  categories.value?.member?.map(cat => ({
    label: cat.name,
    value: cat.id
  })) || []
)

// Images
const existingImagePreviews = ref(
  Array.isArray(props.initialData.images) ? [...props.initialData.images] : []
)
const newImagePreviews = ref([])

// Main image
const mainImageId = ref(props.initialData.mainImage?.id || (existingImagePreviews.value[0]?.id ?? null))

// Gestion fichiers
const onFileChange = (event) => {
  const selectedFiles = Array.from(event.target.files)
  selectedFiles.forEach(file => {
    const tempId = 'new-' + tempImageId++
    form.files.push({ file, tempId })
    newImagePreviews.value.push({ url: URL.createObjectURL(file), tempId })
  })
  event.target.value = ''
}

const removeExistingImage = (id) => {
  existingImagePreviews.value = existingImagePreviews.value.filter(img => img.id !== id)
  if (mainImageId.value === id) mainImageId.value = existingImagePreviews.value[0]?.id ?? newImagePreviews.value[0]?.tempId ?? null
}

const removeNewImage = (index) => {
  const removed = newImagePreviews.value.splice(index, 1)[0]
  form.files = form.files.filter(f => f.tempId !== removed.tempId)
  if (mainImageId.value === removed.tempId) mainImageId.value = existingImagePreviews.value[0]?.id ?? newImagePreviews.value[0]?.tempId ?? null
}

// Submission
const existingImages = computed(() => existingImagePreviews.value.map(img => img.id))

const onSubmit = () => {
  const payload = new FormData()
  payload.append('title', form.title)
  if (form.description) payload.append('description', form.description)
  payload.append('price', form.price)
  payload.append('visible', form.visible)
  payload.append('stock', form.stock)
  payload.append('categoriesIds', JSON.stringify(form.categoryId))
  payload.append('mainImageId', mainImageId.value)
  if (form.mosaicPosition !== null && form.mosaicPosition !== undefined && form.mosaicPosition !== '') {
    payload.append('mosaicPosition', form.mosaicPosition)
  } else {
    payload.append('mosaicPosition', '')
  }

  form.files.forEach(f => payload.append('files[]', f.file))
  payload.append('existingImages', JSON.stringify(existingImages.value))

  emit('submit', payload)
}
</script>
