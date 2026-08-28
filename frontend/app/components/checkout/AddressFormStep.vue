<template>
  <div class="w-full space-y-8">
    <!-- Adresses de livraison -->
    <div>
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
        <div>
          <h2 class="text-lg font-bold text-neutral-900">
            Adresse de livraison
          </h2>
          <p class="text-xs text-neutral-500">
            Choisissez l'adresse où nous devons expédier vos articles
          </p>
        </div>

        <button
          type="button"
          class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-neutral-300 hover:border-neutral-900 bg-white hover:bg-neutral-50 text-neutral-800 text-xs font-semibold transition-all cursor-pointer shadow-2xs self-start sm:self-auto"
          @click="openAddressModal()"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          <span>Ajouter une adresse</span>
        </button>
      </div>

      <div v-if="addresses.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div
          v-for="address in addresses"
          :key="address.id"
          @click="selectedAddressId = address.id"
          :class="[
            'relative p-5 rounded-xl transition-all cursor-pointer flex flex-col justify-between',
            selectedAddressId === address.id
              ? 'border-2 border-neutral-900 bg-neutral-50/80 shadow-xs ring-2 ring-neutral-900/5'
              : 'border border-neutral-200/90 hover:border-neutral-300 hover:bg-neutral-50/40 bg-white'
          ]"
        >
          <!-- Indicateur de sélection + Contenu -->
          <div class="flex items-start gap-3">
            <div class="pt-0.5">
              <div
                :class="[
                  'w-5 h-5 rounded-full border flex items-center justify-center transition-colors',
                  selectedAddressId === address.id
                    ? 'border-neutral-900 bg-neutral-900 text-white'
                    : 'border-neutral-300 bg-white'
                ]"
              >
                <div v-if="selectedAddressId === address.id" class="w-2 h-2 rounded-full bg-white"></div>
              </div>
            </div>

            <div class="flex-1">
              <AddressCard :address="address" />
            </div>
          </div>

          <!-- Actions modifier / supprimer -->
          <div class="flex items-center justify-end gap-2 mt-4 pt-3 border-t border-neutral-200/60" @click.stop>
            <button
              type="button"
              @click="editAddress(address)"
              class="inline-flex items-center gap-1.5 text-xs text-neutral-600 hover:text-neutral-950 font-medium px-2 py-1 rounded hover:bg-neutral-200/60 transition-colors cursor-pointer"
            >
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
              </svg>
              <span>Modifier</span>
            </button>

            <button
              type="button"
              @click="confirmDelete(address)"
              class="inline-flex items-center gap-1.5 text-xs text-rose-600 hover:text-rose-700 font-medium px-2 py-1 rounded hover:bg-rose-50 transition-colors cursor-pointer"
            >
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
              <span>Supprimer</span>
            </button>
          </div>
        </div>
      </div>

      <div v-else class="p-8 text-center bg-neutral-50 rounded-xl border border-dashed border-neutral-300">
        <p class="text-sm text-neutral-500 mb-3">Vous n'avez pas encore d'adresse enregistrée.</p>
        <button
          type="button"
          @click="openAddressModal()"
          class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-neutral-900 text-white text-xs font-semibold hover:bg-neutral-800 transition-colors"
        >
          Créer ma première adresse
        </button>
      </div>
    </div>

    <!-- Facturation différente -->
    <div class="pt-6 border-t border-neutral-200/80">
      <label class="flex items-center gap-3 p-4 rounded-xl border border-neutral-200 hover:border-neutral-300 bg-neutral-50/50 cursor-pointer transition-colors">
        <input
          type="checkbox"
          v-model="billingDifferent"
          class="w-4 h-4 rounded border-neutral-300 text-neutral-900 focus:ring-neutral-900 cursor-pointer"
        />
        <span class="text-sm font-semibold text-neutral-800">
          Utiliser une adresse de facturation différente
        </span>
      </label>

      <transition name="fade">
        <div v-if="billingDifferent" class="mt-6 space-y-4">
          <div>
            <h2 class="text-lg font-bold text-neutral-900">
              Adresse de facturation
            </h2>
            <p class="text-xs text-neutral-500">
              Sélectionnez l'adresse qui figurera sur votre facture
            </p>
          </div>

          <div v-if="addresses.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div
              v-for="address in addresses"
              :key="address.id"
              @click="selectedBillingAddressId = address.id"
              :class="[
                'relative p-5 rounded-xl transition-all cursor-pointer flex flex-col justify-between',
                selectedBillingAddressId === address.id
                  ? 'border-2 border-neutral-900 bg-neutral-50/80 shadow-xs ring-2 ring-neutral-900/5'
                  : 'border border-neutral-200/90 hover:border-neutral-300 hover:bg-neutral-50/40 bg-white'
              ]"
            >
              <div class="flex items-start gap-3">
                <div class="pt-0.5">
                  <div
                    :class="[
                      'w-5 h-5 rounded-full border flex items-center justify-center transition-colors',
                      selectedBillingAddressId === address.id
                        ? 'border-neutral-900 bg-neutral-900 text-white'
                        : 'border-neutral-300 bg-white'
                    ]"
                  >
                    <div v-if="selectedBillingAddressId === address.id" class="w-2 h-2 rounded-full bg-white"></div>
                  </div>
                </div>

                <div class="flex-1">
                  <AddressCard :address="address" />
                </div>
              </div>
            </div>
          </div>
          <div v-else class="p-6 text-center text-sm text-neutral-500 bg-neutral-50 rounded-xl">
            Aucune adresse disponible.
          </div>
        </div>
      </transition>
    </div>
  </div>

  <!-- Modal -->
  <UModal
    :title="modalTitle"
    :close="{
      color: 'error',
      variant: 'outline',
      class: 'rounded-full cursor-pointer'
    }"
    v-model:open="addressModalIsOpen"
  >
    <template #body>
      <AddressForm :address="editingAddress" @submit="submitAddress" />
    </template>
  </UModal>

  <UModal v-model:open="deleteDialogOpen">
      <template #header>
        <h3 class="text-lg font-bold">Supprimer l'adresse</h3>
      </template>
      <template #body>
      <p>
        Es-tu sûr de vouloir supprimer cette adresse ?
        Cette action est irréversible.
      </p>
      </template>
      <template #footer>
        <div class="flex justify-end gap-2">
          <UButton color="gray" variant="outline" @click="deleteDialogOpen = false" class="cursor-pointer">
            Annuler
          </UButton>
          <UButton color="error" @click="deleteAddress" class="cursor-pointer">
            Supprimer
          </UButton>
        </div>
      </template>
  </UModal>
</template>

<script setup>
import useAddressService from '~/services/AddressApi'
const formStore = useFormStepperStore()
const ui = useUiStore()
const props = defineProps({ addresses: { type: Array, default: () => [] } })
const emits = defineEmits(['refreshAddresses'])

const selectedAddressId = ref(null)
const selectedBillingAddressId = ref(null)
const billingDifferent = ref(false)

const addressModalIsOpen = ref(false)
const editingAddress = ref(null)
const modalTitle = computed(() =>
  editingAddress.value ? "Modifier l'adresse" : 'Ajouter une adresse'
)

// init livraison
if (formStore.address) {
  selectedAddressId.value = formStore.address.id
}

watch(selectedAddressId, (newId) => {
  formStore.address = props.addresses.find((addr) => addr.id === newId) || null
})

// init facturation
watch(selectedBillingAddressId, (newId) => {
  formStore.billingAddress =
    props.addresses.find((addr) => addr.id === newId) || null
})

watch(billingDifferent, (isDiff) => { 
  if (isDiff) {
    formStore.billingAddress = null;
  } else {
    formStore.billingAddress = formStore.address;
  }
})

watch(
  [() => billingDifferent.value, () => formStore.address],
  ([isDiff, addr]) => {
    if (!isDiff) {
      formStore.billingAddress = addr
    }
  }
)

const editAddress = (address) => {
  editingAddress.value = address
  addressModalIsOpen.value = true
}

const openAddressModal = () => {
  editingAddress.value = null
  addressModalIsOpen.value = true
}

const { $apiFetch } = useNuxtApp()
const addressService = useAddressService($apiFetch)
const submitAddress = async (addressData) => {
  try {
    ui.showLoader()
    if (editingAddress.value) {
      await addressService.update(editingAddress.value.id, addressData)
    } else {
      await addressService.create(addressData)
    }
    addressModalIsOpen.value = false;
    emits('refreshAddresses')
    ui.hideLoader()
  } catch (err) {
    ui.hideLoader()
    console.error("Erreur lors de la sauvegarde de l'adresse:", err)
    const toast = useToast()
    toast.add({
      title: 'Erreur',
      description: "Impossible de sauvegarder l'adresse. Veuillez réessayer.",
      color: 'danger'
    })
  }
}

const deleteDialogOpen = ref(false)
const addressToDelete = ref(null)

const confirmDelete = (address) => {
  addressToDelete.value = address
  deleteDialogOpen.value = true
}

const deleteAddress = async () => {
  try {
    ui.showLoader()
    await addressService.delete(addressToDelete.value.id)
    deleteDialogOpen.value = false
    emits('refreshAddresses')
    ui.hideLoader()
  } catch (err) {
    ui.hideLoader()
    console.error("Erreur suppression adresse:", err)
    const toast = useToast()
    toast.add({
      title: 'Erreur',
      description: "Impossible de supprimer l'adresse. Veuillez réessayer.",
      color: 'danger'
    })
  }
}
</script>
