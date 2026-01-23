import { productsColumns } from './productColDef'
import { usersColumns } from './userColDef'
import { categoryColumns } from './categoryColDef'
import { shippingMethodColumns } from './shippingMethodColDef'
import { ordersColumns } from './orderColDef'

export const columnsMap = {
    produits: productsColumns,
    utilisateurs: usersColumns,
    categories: categoryColumns,
    expeditions: shippingMethodColumns,
    commandes: ordersColumns
}
