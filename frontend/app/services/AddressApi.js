export default function useAddressService($apiFetch) {
  return {
    async create(payload) {
      return $apiFetch('/api/addresses', {
        method: 'POST',
        body: payload
      })
    },

    async update(id, payload) {
      return $apiFetch(`/api/addresses/${id}`, {
        method: 'PATCH',
        body: payload,
        headers: {
          'Content-Type': 'application/merge-patch+json'
        }
      })
    },

    async delete(id) {
      return $apiFetch(`/api/addresses/${id}`, {
        method: 'DELETE'
      })
    }
  }
}