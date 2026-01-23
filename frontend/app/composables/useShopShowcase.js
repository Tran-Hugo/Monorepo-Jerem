export const useShopShowcase = () => {
    const { $apiFetch } = useNuxtApp()

    return useAsyncData('shopShowcase', () => $apiFetch('/api/stats/showcase'))
}