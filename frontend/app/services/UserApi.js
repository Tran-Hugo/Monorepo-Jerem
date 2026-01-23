// services/cartService.ts
export default function useUserService($apiFetch) {
  return {

    async register(form) {
      return await $apiFetch('/api/register_user', {
        method: 'POST',
        body: form
      })
    },

  }
}
