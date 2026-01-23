export const useFormStepperStore = defineStore('formStepper', {
  state: () => ({
    personal: { name: '', age: '' },
    address: null,
    shippingMethod:null,
    billingAddress: null
  }),
  actions: {
    reset() {
      this.personal = { name: '', age: '' }
      this.address = { street: '', city: '' }
    }
  }
})
