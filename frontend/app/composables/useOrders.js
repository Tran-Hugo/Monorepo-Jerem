export const useOrders = async () => {
  const { $apiFetch } = useNuxtApp()
  
  return await useAsyncData('orders', () => $apiFetch('/api/orders'))
}

export const useOrder = async (id) => {
  const { $apiFetch } = useNuxtApp()

  return await useAsyncData('order', () =>
    $apiFetch('/api/orders/' + id)
  )
}
