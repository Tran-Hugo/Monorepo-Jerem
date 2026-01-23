export default function useCategoryService($apiFetch) {
  return {
    async create(payload) {
      return $apiFetch('/api/categories', {
        method: 'POST',
        body: payload
      })
    },

    async update(id, payload) {
      return $apiFetch(`/api/categories/${id}`, {
        method: 'PATCH',
        body: payload,
        headers: {
          'Content-Type': 'application/merge-patch+json'
        }
      })
    },

    async delete(id) {
      return $apiFetch(`/api/categories/${id}`, {
        method: 'DELETE'
      })
    }
  }
}
