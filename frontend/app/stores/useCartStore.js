export const useCartStore = defineStore('cart', {
  state: () => ({
    count: 0
  }),
  actions: {
    async fetchCount() {
        const { $apiFetch } = useNuxtApp()
        try {
            const res = await $apiFetch('/api/cart/count')
            this.count = res.count
        } catch {
            this.count = 0
        }
    },
    increment() {
      this.count++
    },
    decrement() {
      if (this.count > 0) this.count--
    },
    clear() {
      this.count = 0
    }
  }
})
