export const categoryColumns = [
  {
    headerName: "Actions",
    field: "actions",
    flex: 1,
    minWidth: 220,
    cellRenderer: (params) => {
      // Crée le conteneur
      const container = document.createElement("div")
      container.className = "flex w-full justify-center gap-2"

      // Bouton Éditer
      const editBtn = document.createElement("button")
      editBtn.innerText = "✏️ Éditer"
      editBtn.className = "bg-blue-500 text-white px-2 rounded flex items-center cursor-pointer"
      editBtn.addEventListener("click", () => {
        params.context.router.push(`/admin/categories/form/${params.data.id}`)
      })

      // Bouton Supprimer
      const deleteBtn = document.createElement("button")
      deleteBtn.innerText = "🗑️ Supprimer"
      deleteBtn.className = "bg-red-500 text-white px-2 rounded flex items-center cursor-pointer"
      deleteBtn.addEventListener("click", async () => {
        if (!confirm(`Supprimer ${params.data.name} ?`)) return
        try {
          await params.context.currentService.value.delete(params.data.id)
          alert(`${params.data.name} supprimé !`)
          // Rafraîchir la grille
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
  { field: "name", flex: 2, minWidth: 200, headerName: "Nom" },
]
