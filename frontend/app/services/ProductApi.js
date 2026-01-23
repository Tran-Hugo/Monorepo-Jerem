export default function useProductService($apiFetch) {
    return {
        async create(payload) {
            return $apiFetch('/api/products/create', {
                method: 'POST',
                body: payload
            })
        },
    
        async update(id, payload) {
            return $apiFetch(`/api/products/${id}/update`, {
                method: 'POST',
                body: payload
            })
        },
    
        async delete(id) {
            return $apiFetch(`/api/products/${id}/delete`, {
                method: 'DELETE'
            })
        }
    }
}