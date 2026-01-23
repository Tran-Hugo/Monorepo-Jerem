export default function useShippingMethodService($apiFetch) {
    return {
        async create(payload) {
            return $apiFetch('/api/shipping_methods/create', {
                method: 'POST',
                body: payload
            })
        },
    
        async update(id, payload) {
            return $apiFetch(`/api/shipping_methods/${id}/update`, {
                method: 'POST',
                body: payload
            })
        },
    
        async delete(id) {
            return $apiFetch(`/api/shipping_methods/${id}/delete`, {
                method: 'DELETE'
            })
        }
    }
}