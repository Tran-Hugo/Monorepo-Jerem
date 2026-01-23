export const useUsers = async () => {
  const { $apiFetch } = useNuxtApp()
  
  return await useAsyncData('users', () => $apiFetch('/api/users'))
}

export const useUser = async (id) => {
  const { $apiFetch } = useNuxtApp()

  return await useAsyncData('user', () =>
    $apiFetch('/api/users/' + id)
  )
}
