export const useAddresses = () => {
    const { $apiFetch } = useNuxtApp()

    return useAsyncData('myAddresses', () => $apiFetch('/api/address/me'))
}