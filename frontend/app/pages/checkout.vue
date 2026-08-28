<template>
  <div class="min-h-screen bg-neutral-50/70 py-8 lg:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <!-- En-tête du tunnel -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 pb-6 border-b border-neutral-200/80">
        <div>
          <NuxtLink to="/cart" class="inline-flex items-center gap-2 text-xs sm:text-sm font-medium text-neutral-500 hover:text-neutral-900 transition-colors mb-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            <span>Retour au panier</span>
          </NuxtLink>
          <h1 class="text-2xl sm:text-3xl font-bold text-neutral-900">Finaliser votre commande</h1>
        </div>

        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold self-start sm:self-auto border border-emerald-200/60">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
          </svg>
          <span>Paiement 100% sécurisé</span>
        </div>
      </div>

      <!-- Stepper visuel moderne -->
      <div class="mb-10 max-w-3xl mx-auto">
        <div class="grid grid-cols-3 gap-2 sm:gap-4 relative">
          <div
            v-for="(item, index) in items"
            :key="index"
            class="flex flex-col items-center text-center relative z-10"
          >
            <div
              :class="[
                'w-10 h-10 sm:w-12 sm:h-12 rounded-2xl flex items-center justify-center font-bold text-sm transition-all duration-300 shadow-xs mb-2',
                currentStep === index
                  ? 'bg-neutral-900 text-white ring-4 ring-neutral-900/10'
                  : currentStep > index
                    ? 'bg-emerald-600 text-white'
                    : 'bg-white text-neutral-400 border border-neutral-200'
              ]"
            >
              <svg v-if="currentStep > index" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
              </svg>
              <span v-else>{{ index + 1 }}</span>
            </div>
            <p :class="['text-xs sm:text-sm font-semibold transition-colors', currentStep === index ? 'text-neutral-950 font-bold' : currentStep > index ? 'text-neutral-800' : 'text-neutral-400']">
              {{ item.title }}
            </p>
            <p class="text-[11px] text-neutral-400 hidden sm:block">
              {{ item.description }}
            </p>
          </div>

          <!-- Barre de progression en arrière-plan -->
          <div class="absolute top-5 sm:top-6 left-1/6 right-1/6 h-0.5 bg-neutral-200 -z-0">
            <div
              class="h-full bg-emerald-600 transition-all duration-300"
              :style="{ width: currentStep === 0 ? '0%' : currentStep === 1 ? '50%' : '100%' }"
            ></div>
          </div>
        </div>
      </div>

      <!-- Grille principale 2 colonnes -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Colonne formulaire étape (7/12) -->
        <div class="lg:col-span-7 xl:col-span-8 bg-white border border-neutral-200/80 rounded-2xl p-6 sm:p-8 shadow-xs">
          <MyStepper
            v-model="currentStep"
            prevLabel="Retour"
            nextLabel="Continuer"
          >
            <MyStepperStep
              :validateStep="addressStepValidate"
            >
              <AddressFormStep
                :addresses="addresses"
                @refreshAddresses="refreshAddresses"
              />
            </MyStepperStep>

            <MyStepperStep
              :validateStep="shippingStepValidate"
            >
              <ShippingFormStep
                :shippingMethods="shippingMethods?.member"
                @refreshShippingMethods="refreshShippingMethods"
              />
            </MyStepperStep>

            <MyStepperStep>
              <PaypalButton />
            </MyStepperStep>
          </MyStepper>
        </div>

        <!-- Colonne résumé de commande (5/12, Sticky) -->
        <aside class="lg:col-span-5 xl:col-span-4 lg:sticky lg:top-28">
          <OrderSummary :cart="cart" :articleTotal="cart?.total" />
        </aside>
      </div>

    </div>
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
