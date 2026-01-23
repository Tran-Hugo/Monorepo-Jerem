export const useGlobalSearchStore = defineStore('globalSearch', {
  state: () => ({
    isOpen: false
  }),
  actions: {
    open() {
      this.isOpen = true
    },
    close() {
      this.isOpen = false
    },
    toggle() {
      this.isOpen = !this.isOpen
    }
  }
})