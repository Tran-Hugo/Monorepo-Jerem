import { defineStore } from 'pinia'

export const useUiStore = defineStore('ui', {
  state: () => ({
    loading: false,
    adminSidebarOpen: false,
    publicSidebarOpen: false
  }),
  actions: {
    showLoader() {
      this.loading = true
    },
    hideLoader() {
      this.loading = false
    },
    toggleAdminSidebar() {
      this.adminSidebarOpen = !this.adminSidebarOpen
    },
    closeAdminSidebar() {
      if (this.adminSidebarOpen) {
        this.adminSidebarOpen = false
      }
    },
    togglePublicSidebar() {
      this.publicSidebarOpen = !this.publicSidebarOpen
    },
    closePublicSidebar() {
      if (this.publicSidebarOpen) {
        this.publicSidebarOpen = false
      }
    }
  }
})
