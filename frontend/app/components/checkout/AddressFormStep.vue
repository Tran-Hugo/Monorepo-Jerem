<template>
  <div class="shadow lg:w-3/5 min-h-[20rem] p-6">
    <!-- Adresses de livraison -->
    <div class="flex flex-col lg:flex-row lg:justify-between items-center mb-4 ">
      <h2 class="font-bold mb-2">
        Adresse de livraison ({{ addresses.length }})
      </h2>
      <UButton
        color="secondary"
        class="mb-4 px-6 py-2 cursor-pointer"
        @click="openAddressModal()"
      >
        Ajouter une adresse
      </UButton>
    </div>

    <div v-if="addresses.length > 0" 
         class="flex flex-col gap-4 max-h-90 overflow-y-auto">
      <div
        v-for="address in addresses"
        :key="address.id"
        class="flex justify-between items-start p-4 rounded hover:shadow-md"
      >
        <label class="flex items-center gap-4 cursor-pointer">
          <input
            type="radio"
            :value="address.id"
            v-model="selectedAddressId"
            class="cursor-pointer"
          />
          <div>
            <AddressCard :address="address" />
            <p @click="editAddress(address)" class="text-blue-600 w-1/2">
              Modifier l'adresse
            </p>
          </div>
        </label>
        <UIcon
          name="ic:round-close"
          class="size-5 cursor-pointer"
          @click="confirmDelete(address)"
        />
      </div>
    </div>
    <div v-else>
      <p>Aucune adresse enregistrée.</p>
    </div>

    <!-- Facturation différente -->
    <div class="mt-6">
      <label class="flex items-center gap-2 cursor-pointer">
        <input type="checkbox" v-model="billingDifferent" />
        <span>Utiliser une adresse de facturation différente</span>
      </label>

      <transition name="fade">
        <div v-if="billingDifferent" class="mt-4 border-t pt-4">
          <h2 class="font-bold mb-2">
            Adresse de facturation ({{ addresses.length }})
          </h2>

          <div v-if="addresses.length > 0" 
               class="flex flex-col gap-4 max-h-90 overflow-y-auto">
            <div
              v-for="address in addresses"
              :key="address.id"
              class="flex justify-between items-start p-4 rounded hover:shadow-md"
            >
              <label class="flex items-center gap-4 cursor-pointer">
                <input
                  type="radio"
                  :value="address.id"
                  v-model="selectedBillingAddressId"
                  class="cursor-pointer"
                />
                <div>
                  <AddressCard :address="address" />
                  <p
                    @click="editAddress(address)"
                    class="text-blue-600 w-1/2"
                  >
                    Modifier l'adresse
                  </p>
                </div>
              </label>
            </div>
          </div>
          <div v-else>
            <p>Aucune adresse enregistrée.</p>
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
