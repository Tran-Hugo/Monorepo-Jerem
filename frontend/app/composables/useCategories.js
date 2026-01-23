export const useCategories = async () => {
  const { $apiFetch } = useNuxtApp()
  
  return await useAsyncData('categories', () => $apiFetch('/api/categories'))
}

export const useCategory = async (id) => {
  const { $apiFetch } = useNuxtApp()

  return await useAsyncData('category', () =>
    $apiFetch('/api/categories/' + id)
  )
}
