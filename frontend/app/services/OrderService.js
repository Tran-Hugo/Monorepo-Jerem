export default function useOrderService($apiFetch) {
  return {

    async shipOrder(orderId) {
      return await $apiFetch(`/api/orders/${orderId}/ship`, {
        method: 'POST'
      })
    },

    async cancelOrder(orderId, reason = "") {
      return await $apiFetch(`/api/orders/${orderId}/cancel`, {
        method: 'POST',
        body: JSON.stringify({ reason })
      })
    }
  }
}
