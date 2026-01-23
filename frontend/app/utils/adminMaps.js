// ~/utils/adminMaps.js

// Formulaires
import CategoryForm from '@/components/admin/CategoryForm.vue'
import ProductForm from '@/components/admin/ProductForm.vue'
import ShippinMethodForm from '@/components/admin/ShippinMethodForm.vue'

// Services
import useCategoryService from '@/services/CategoryApi'
import useProductService from '@/services/ProductApi'
import useShippingMethodService from '@/services/ShippingMethodApi'

export const formsMap = {
  categories: CategoryForm,
  produits: ProductForm,
  expeditions: ShippinMethodForm
}

export const composablesMap = {
  categories: useCategory,
  produits: useProduct,
  expeditions: useShippingMethod
}

export const collectionsComposablesMap = {
    categories: useCategories,
    produits: useProducts,
    utilisateurs: useUsers,
    expeditions: useShippingMethods,
    commandes: useOrders
}

export const servicesMap = {
  categories: useCategoryService,
  produits: useProductService,
  expeditions: useShippingMethodService
}
