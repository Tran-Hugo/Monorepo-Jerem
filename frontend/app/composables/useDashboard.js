export const useDashboard = () => {
    const { $apiFetch } = useNuxtApp()

    return useAsyncData('dashboard', () => $apiFetch('/api/stats/dashboard'))
}