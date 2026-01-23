import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null
  }),

  getters: {
    isAuthenticated: (state) => !!state.user
  },

  actions: {
    async fetchUser() {
      const { $apiFetch } = useNuxtApp()
      try {
        const user = await $apiFetch('/api/me')
        this.user = user || null
      } catch (err) {
        if (import.meta.server) { // En SSR, éviter le crash (ne pas throw)
          this.user = null
          return
        }
      }
    },

    async login(email, password) {
      const { $apiFetch } = useNuxtApp()

      await $apiFetch('/api/login_check', {
        method: 'POST',
        body: { username: email, password }
      })

      await this.fetchUser()
    },

    async logout() {
      const { $apiFetch } = useNuxtApp()
      const router = useRouter()

      try {
        await $apiFetch('/api/logout', { method: 'POST' })
        const cartStore = useCartStore()
        cartStore.fetchCount()
      } catch (e) {
        console.warn("Erreur lors de la déconnexion :", e);
      }
      
      this.user = null
      router.push('/')
    }
  }
})
