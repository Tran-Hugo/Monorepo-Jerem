import { formatPrice } from '@/utils/formatPrice'

export const ordersColumns = [
  {
    headerName: "Actions",
    field: "actions",
    flex: 1,
    minWidth: 220,
    cellRenderer: (params) => {
      const container = document.createElement("div")
      container.className = "flex w-full justify-center gap-2 items-center h-full whitespace-nowrap"

      const openBtn = document.createElement("button")
      openBtn.innerText = "Voir la commande"
      openBtn.className = "bg-green-500 text-white px-2 rounded flex items-center cursor-pointer"
      openBtn.addEventListener("click", async () => {
        params.context.router.push(`/admin/commande/${params.data.id}`)
      })

      container.appendChild(openBtn)

      return container
    },
    filter: false
  },
  {
    field: "id",
    headerName: "numéro de commande",
    flex: 1,
    minWidth: 120,
    autoHeight: true,
  },
  { 
    field: "user",
    headerName: "Client",
    flex: 1,
    minWidth: 200,
    valueFormatter: (params) => {
        return params.value?.firstname + " " + params.value?.lastname;
    }
  },
  { 
    field: "user.email",
    flex: 2,
    minWidth: 100
  },
  { 
    field: "status",
    headerName: "Statut",
    flex: 2,
    minWidth: 100,
    valueGetter: (params) => {
      switch (params.data.status) {
        case "pending": return "En attente"
        case "shipped": return "Expédiée"
        case "paid": return "Payée en attente d'expédition"
        case "cancelled": return "Annulée"
        default: return params.data.status
      }
    },
    cellRenderer: (params) => {
        const status = params.data.status;
        let colorClass = "";
        let statusText = "";
        switch (status) {
            case "pending":
                colorClass = "bg-yellow-500";
                statusText = "En attente";
                break;
            case "shipped":
                colorClass = "bg-green-500";
                statusText = "Expédiée";
                break;
            case "paid":
                colorClass = "bg-blue-500";
                statusText = "Payée en attente d'expédition";
                break;
            case "cancelled":
                colorClass = "bg-red-500";
                statusText = "Annulée";
                break;
            default:
                colorClass = "bg-gray-500";
                statusText = status;
                break;
        }
        
        return `<span class="text-white px-2 py-1 rounded ${colorClass}">${statusText}</span>`;
    }
  },
  { 
    field: "total",
    headerName: "Total",
    flex: 1,
    minWidth: 120,
    valueFormatter: (params) => {
      return formatPrice(Number(params.value));
    }
  },
  {
    field: "createdAt",
    headerName: "Date de commande",
    flex: 1,
    minWidth: 180,
    valueFormatter: (params) => {
      const date = new Date(params.value);
      return date.toLocaleDateString() + " " + date.toLocaleTimeString();
    }
  }
]
