export const useShippingMethods = () => {
    const { $apiFetch } = useNuxtApp()

    return useAsyncData('shippingMethods', () => $apiFetch('/api/shipping_methods'))
}

export const useShippingMethod = async (id) => {
  const { $apiFetch } = useNuxtApp()

  return await useAsyncData('shippingMethod', () =>
    $apiFetch('/api/shipping_methods/' + id)
  )
}