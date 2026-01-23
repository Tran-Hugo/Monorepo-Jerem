// services/cartService.ts
export default function cartService($apiFetch) {
  return {

    async addProduct(productId, quantity) {
      return await $apiFetch('/api/cart/add', {
        method: 'POST',
        body: { productId, quantity },
      })
    },

    async updateQuantity(productId, quantity) {
      return await $apiFetch('/api/cart_items/' + productId, {
        method: 'PATCH',
        body: { quantity },
        headers: {
          'Content-Type': 'application/merge-patch+json'
        }
      })
    },

    async removeProduct(productId) {
      return await $apiFetch('/api/cart_items/' + productId, {
        method: 'DELETE'
      })
    },
  }
}
