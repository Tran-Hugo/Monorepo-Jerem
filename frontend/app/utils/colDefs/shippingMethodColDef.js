import { formatPrice } from '@/utils/formatPrice'

export const shippingMethodColumns = [
  {
    headerName: "Actions",
    field: "actions",
    flex: 1,
    minWidth: 220,
    cellRenderer: (params) => {
      const container = document.createElement("div")
      container.className = "flex w-full justify-center gap-2 items-center h-full whitespace-nowrap"

      const editBtn = document.createElement("button")
      editBtn.innerText = "✏️ Éditer"
      editBtn.className = "bg-blue-500 text-white px-2 rounded flex items-center cursor-pointer"
      editBtn.addEventListener("click", () => {
        params.context.router.push(`/admin/expeditions/form/${params.data.id}`)
      })

      const deleteBtn = document.createElement("button")
      deleteBtn.innerText = "🗑️ Supprimer"
      deleteBtn.className = "bg-red-500 text-white px-2 rounded flex items-center cursor-pointer"
      deleteBtn.addEventListener("click", async () => {
        if (!confirm(`Supprimer ${params.data.name} ?`)) return
        try {
          await params.context.currentService.value.delete(params.data.id)
          alert(`${params.data.name} supprimé !`)
          params.api.applyTransaction({ remove: [params.data] })
        } catch (e) {
          console.error(e)
          alert(`Erreur lors de la suppression de ${params.data.name}`)
        }
      })

      container.appendChild(editBtn)
      container.appendChild(deleteBtn)

      return container
    },
    filter: false
  },
  {
    field: "image",
    headerName: "Image",
    flex: 1,
    minWidth: 120,
    autoHeight: true,
    cellRenderer: (params) => {
      console.log(params.data);
      
      if (!params.data.image) return "Aucune image"
      const url = params.context.baseUrl + "/" + params.data.image.path
      return `<img src="${url}" alt="${params.data.title}" class="w-16 h-16 object-cover rounded m-1" />`
    },
    filter: false
  },
  { 
    field: "name",
    headerName: "Nom",
    flex: 1,
    minWidth: 200
  },
  { 
    field: "description",
    flex: 2,
    minWidth: 100
  },
  { 
    field: "price",
    headerName: "Prix",
    flex: 1,
    minWidth: 120,
    valueFormatter: (params) => {
      return formatPrice(Number(params.value));
    }
  },
]
