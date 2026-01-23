<template>
  <div class="flex flex-col lg:flex-row w-full min-h-screen">
    <!-- Colonne principale -->
    <div class="w-full lg:w-4/5 px-4 lg:px-20 py-10">
      <!-- Stepper -->
      <UStepper
        color="neutral"
        :items="items"
        v-model="currentStep"
        class="w-full"
        :disabled="true"
      />

      <!-- Formulaire -->
      <MyStepper
        v-model="currentStep"
        class="mt-14"
        prevLabel="Annuler"
        nextLabel="Valider"
      >
        <MyStepperStep
          class="flex justify-center"
          :validateStep="addressStepValidate"
        >
          <AddressFormStep
            :addresses="addresses"
            @refreshAddresses="refreshAddresses"
          />
        </MyStepperStep>

        <MyStepperStep
          class="flex justify-center"
          :validateStep="shippingStepValidate"
        >
          <ShippingFormStep
            :shippingMethods="shippingMethods?.member"
            @refreshShippingMethods="refreshShippingMethods"
          />
        </MyStepperStep>

        <MyStepperStep class="flex justify-center">
          <PaypalButton />
        </MyStepperStep>
      </MyStepper>
    </div>

    <!-- Sidebar -->
    <aside class="w-full lg:w-1/5 bg-gray-200 px-4 py-6 lg:min-h-screen">
      <OrderSummary :articleTotal="cart?.total"/>
    </aside>
  </div>
</template>

<script setup>

const toast = useToast()
    
const currentStep = ref(0)

const items = ref([
  {
    title: 'Adresse',
    description: 'Entrez votre adresse de livraison',
    icon: 'i-lucide-house'
  },
  {
    title: 'Livraison',
    description: 'Choisissez votre mode de livraison',
    icon: 'i-lucide-truck'
  },
  {
    title: 'Paiement',
    description: 'Effectuez votre paiement',
    icon: 'i-lucide-credit-card'
  }
])

const ui = useUiStore()
ui.showLoader()

const { data: cart, error, refresh } = await useCart()
const { data: addresses, refresh: refreshAddresses } = await useAddresses();
const { data: shippingMethods, refresh: refreshShippingMethods } = await useShippingMethods();

ui.hideLoader()

const formStore = useFormStepperStore()
const addressStepValidate = () => {
  const isValid = !!formStore.address && !!formStore.billingAddress
  if (!isValid) {
    toast.add({
      title: 'Erreur',
      description: 'Veuillez sélectionner une adresse de livraison et une adresse de facturation.',
      color: 'warning',
      icon: 'ic:baseline-error'
    })
  }
  return isValid
}

const shippingStepValidate = () => {
  const isValid = !!formStore.shippingMethod
  if (!isValid) {
    toast.add({
      title: 'Erreur',
      description: 'Veuillez sélectionner une méthode de livraison.',
      color: 'warning',
      icon: 'ic:baseline-error'
    })
  }
  return isValid
}
</script>
