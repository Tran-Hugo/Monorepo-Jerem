export const useProducts = async () => {
  const { $apiFetch } = useNuxtApp()
  return await useAsyncData('products', () => $apiFetch('/api/products?order[id]=desc'))
}

export const useProduct = async (id) => {
  const { $apiFetch } = useNuxtApp()

  return await useAsyncData('product', () =>
    $apiFetch('/api/products/' + id)
  )
}

export const useProductBySlug = async (slug) => {
  const { $apiFetch } = useNuxtApp()

  return await useAsyncData('product', () =>
    $apiFetch('/api/products/by-slug/' + slug)
  )
}

export const useSearchProducts = async (query = null, sort = null, category = null, page = null, limit = null) => {
  const { $apiFetch } = useNuxtApp()

  let url = '/api/search?';

  if (query) url += `q=${query}&`;
  if (sort) url += `sort=${sort}&`;
  if (category) url += `category=${category}&`;
  if (page) url += `page=${page}&`;
  if (limit) url += `limit=${limit}&`;

  return await useAsyncData('search', () =>
    $apiFetch(url)
  )
}
