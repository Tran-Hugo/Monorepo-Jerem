export default defineNuxtPlugin((nuxtApp) => {
  const config = useRuntimeConfig()
  const baseURL = config.public.NUXT_PUBLIC_API_BASE_URL // client → browser
  const cartStore = useCartStore()
  const ui = useUiStore()
  const auth = useAuthStore()
  const router = useRouter()
  const toast = useToast()

  const apiFetch = $fetch.create({ 
    baseURL,
    credentials: 'include',
    headers: useRequestHeaders(['cookie']),
    async onResponseError({ request, response }) {
      if (response.status === 401) {
        ui.hideLoader()
        if (auth.user) {
          auth.user = null
          cartStore.fetchCount()
          toast.add({
            title: 'Session expirée',
            description: 'Vous avez été déconnecté.',
            color: 'warning'
          })
          router.push('/login')
        }
      }
    }
  });

  return {
    provide: {
      apiFetch
    }
  }
})
