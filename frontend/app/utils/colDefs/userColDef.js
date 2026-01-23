export const usersColumns = [
  { field: "id", headerName: "ID", flex: 1 },
  { field: "email", headerName: "Email", flex: 2 },
  { field: "roles", headerName: "Rôles", flex: 2, valueFormatter: params => params.value?.join(", ") },
  { field: "firstname", headerName: "Prénom", flex: 1 },
  { field: "lastname", headerName: "Nom", flex: 1 },
]
