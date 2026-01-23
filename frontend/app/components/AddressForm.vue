<template>
  <form @submit.prevent="submitForm">
    <div class="px-6 grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Colonne gauche -->
      <UFormField label="Prénom" size="xl" class="col-span-1">
        <UInput v-model="formData.firstname" placeholder="Entrez un prénom" color="neutral"/>
      </UFormField>
      <UFormField label="Nom" size="xl" class="col-span-1">
        <UInput v-model="formData.lastname" placeholder="Entrez un nom" color="neutral"/>
      </UFormField>

      <!-- Colonne droite -->
      <UFormField label="Rue" size="xl" class="col-span-1">
        <UInput v-model="formData.street" placeholder="Entrez une rue" color="neutral"/>
      </UFormField>

      <UFormField label="Ville" size="xl" class="col-span-1">
        <UInput v-model="formData.city" placeholder="Entrez une ville" color="neutral"/>
      </UFormField>

      <UFormField label="Code Postal" size="xl" class="col-span-1">
        <UInput v-model="formData.postalCode" placeholder="Entrez un code postal" color="neutral"/>
      </UFormField>

      <UFormField label="Pays" size="xl" class="col-span-1">
        <UInputMenu
          v-model="formData.country"
          :items="countries"
          valueKey="value"
          labelKey="label"
          placeholder="Rechercher un pays"
          color="neutral"
          @update:searchTerm="onCountryInput"
        />
      </UFormField>

      <UFormField label="N° de téléphone" size="xl" class="col-span-1">
        <UInput v-model="formData.phone" placeholder="Entrez un n° de téléphone" color="neutral"/>
      </UFormField>
    </div>

    <UButton type="submit" class="px-6 py-3 mt-6 mx-6 cursor-pointer" color="primary">
      Valider
    </UButton>
  </form>
</template>

<script setup>
const { $apiFetch } = useNuxtApp()
const auth = useAuthStore()

const props = defineProps({
  address: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['submit'])

// état local du formulaire
const formData = ref({
  firstname: '',
  lastname: '',
  street: '',
  city: '',
  postalCode: '',
  country: '',
  phone: ''
})

// items pour le select async
const countries = ref([])

// debounce timer
let debounceTimer = null

// Préremplir le pays si address existe
if (props.address?.country) {
  const country = props.address.country
  countries.value.push({
    label: country.name,
    value: `/api/countries/${country.id}`
  })
  formData.value.country = `/api/countries/${country.id}`
}

// watcher pour mettre à jour le formulaire lors de l'édition
watch(
  () => props.address,
  (newAddress) => {
    if (newAddress) {
      formData.value = {
        firstname: newAddress.firstname ?? '',
        lastname: newAddress.lastname ?? '',
        street: newAddress.street ?? '',
        city: newAddress.city ?? '',
        postalCode: newAddress.postalCode ?? '',
        phone: newAddress.phone ?? '',
        country: newAddress.country ? `/api/countries/${newAddress.country.id}` : ''
      }

      // Préremplir le select si le pays existe
      if (newAddress.country) {
        const exists = countries.value.find(c => c.value === `/api/countries/${newAddress.country.id}`)
        if (!exists) {
          countries.value.push({
            label: newAddress.country.name,
            value: `/api/countries/${newAddress.country.id}`
          })
        }
      }

    } else {
      formData.value = {
        firstname: '',
        lastname: '',
        street: '',
        city: '',
        postalCode: '',
        country: '',
        phone: ''
      }
    }
  },
  { immediate: true, deep: true }
)

// Recherche async des pays
const fetchCountries = async (search) => {
  if (!search) return

  const res = await $apiFetch(`/api/countries?name=${encodeURIComponent(search)}`)
  countries.value = res["member"].map(c => ({
    label: c.name,
    value: c["@id"]
  }))
}

// debounce en JS natif
const onCountryInput = (search) => {
  if (debounceTimer) clearTimeout(debounceTimer)
  debounceTimer = setTimeout(() => {
    fetchCountries(search)
  }, 300)
}

// soumission du formulaire
const submitForm = () => {
  emit('submit', { ...formData.value, user: `/api/users/${auth.user.id}` })
}
</script>
