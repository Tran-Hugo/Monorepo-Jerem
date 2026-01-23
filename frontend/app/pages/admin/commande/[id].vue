<template>
    <OrderRead :order="data" />
    <div class="m-auto w-1/2 flex justify-around items-center">
        <button 
            @click="cancelOrder"
            v-if="data && data.status == 'paid'"
            class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded shadow cursor-pointer mt-4"
        >
            Annuler la commande
        </button>
        <button 
            @click="shipOrder"
            v-if="data && data.status == 'paid'"
            class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded shadow cursor-pointer mt-4"
        >
            Marquer comme expédiée
        </button>
    </div>
</template>

<script setup>
    import useOrderService from '~/services/OrderService'

    definePageMeta({ layout: 'admin' })
    const route = useRoute()
    const entityId = route.params.id
    const ui = useUiStore()
    ui.showLoader()
    const { data, error, refresh } = await useOrder(entityId)
    if (error.value) {
        console.error("Erreur lors de la récupération de la commande :", error.value)
    }
    ui.hideLoader()
    const { $apiFetch } = useNuxtApp()
    const orderService = useOrderService($apiFetch)
    const toast = useToast()

    async function shipOrder(){
        if (!confirm(`Marquer la commande ${entityId} comme expédiée ?`)) return
        try{
            ui.showLoader()
            await orderService.shipOrder(entityId)
            await refresh()
            ui.hideLoader()
        } catch (error) {
            ui.hideLoader()
            console.error("Erreur lors de la mise à jour de la commande :", error)
            toast.add({
            title: 'Erreur',
            description: "Échec de la mise à jour de la commande.",
            color: 'error'
            })
        }
    }

    async function cancelOrder() {
        if (!confirm(`Annuler la commande ${entityId} ?`)) return

        const input = prompt("Veuillez indiquer la raison de l'annulation (facultatif) :")

        const reason = (input === null || input === "") ? null : input

        
        try {
            ui.showLoader()
            await orderService.cancelOrder(entityId, reason)
            await refresh()
            ui.hideLoader()
        } catch (error) {
            ui.hideLoader()
            console.error("Erreur lors de l'annulation de la commande :", error)
            toast.add({
            title: 'Erreur',
            description: "Échec de l'annulation de la commande.",
            color: 'error'
            })
        }
    }
</script>