export default defineNuxtRouteMiddleware((to, from) => {
  const auth = useAuthStore()

  if (to.path.startsWith('/admin')) {
    if (!auth.isAuthenticated || !auth.user.roles.includes('ROLE_ADMIN')) {
      return navigateTo('/login')
    }
  }
})