import { formatPrice } from '@/utils/formatPrice'

export const productsColumns = [
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
        params.context.router.push(`/admin/produits/form/${params.data.id}`)
      })

      const deleteBtn = document.createElement("button")
      deleteBtn.innerText = "🗑️ Supprimer"
      deleteBtn.className = "bg-red-500 text-white px-2 rounded flex items-center cursor-pointer"
      deleteBtn.addEventListener("click", async () => {
        if (!confirm(`Supprimer ${params.data.title} ?`)) return
        try {
          await params.context.currentService.value.delete(params.data.id)
          alert(`${params.data.title} supprimé !`)
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
    field: "mainImage",
    headerName: "Image",
    flex: 1,
    minWidth: 120,
    autoHeight: true,
    cellRenderer: (params) => {
      if (!params.data.mainImage) return "Aucune image"
      const url = params.context.baseUrl + "/" + params.data.mainImage.path
      return `<img src="${url}" alt="${params.data.title}" class="w-16 h-16 object-cover rounded m-1" />`
    },
    filter: false
  },
  { 
    field: "title",
    headerName: "Titre",
    flex: 2,
    minWidth: 200
  },
  { 
    field: "mosaicPosition",
    headerName: "Mosaïque",
    flex: 1.5,
    minWidth: 160,
    cellRenderer: (params) => {
      const pos = params.data.mosaicPosition;
      if (!pos) return '<span class="text-gray-400 text-xs italic">-</span>';
      if (pos === 1) return '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300">⭐ Slot 1 (Haut-G)</span>';
      if (pos === 9) return '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300">⭐ Slot 9 (Bas-D)</span>';
      return `<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">Slot ${pos}</span>`;
    }
  },
  { 
    field: "stock",
    flex: 1,
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
  { 
    field: "visible",
    headerName: "Visible",
    flex: 1,
    minWidth: 100
  },
  {
    field: "deleted",
    headerName: "Supprimé",
    flex: 1,
    minWidth: 120
  },
  {
    field: "categories",
    headerName: "Catégories",
    flex: 2,
    minWidth: 200,
    valueFormatter: params => {
      return params.value?.map(cat => cat.name).join(", ") || "Aucune catégorie"
    }
  }
]
