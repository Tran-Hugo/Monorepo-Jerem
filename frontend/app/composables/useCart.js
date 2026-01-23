export const useCart =  () => {
  const { $apiFetch } = useNuxtApp()
  
  return useAsyncData('cart', () => $apiFetch('/api/cart'))
}