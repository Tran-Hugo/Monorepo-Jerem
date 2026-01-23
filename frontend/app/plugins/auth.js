import { useAuthStore } from '~/stores/useAuthStore'

export default defineNuxtPlugin(async () => {
  const auth = useAuthStore()

  if (!auth.user) {
    await auth.fetchUser()
  }
})