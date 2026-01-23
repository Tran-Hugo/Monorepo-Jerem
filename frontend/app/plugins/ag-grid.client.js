
console.log('AG Grid Community modules registered');
import { AgGridVue } from 'ag-grid-vue3'
import { AllCommunityModule, ModuleRegistry } from 'ag-grid-community'; 

// Register all Community features
ModuleRegistry.registerModules([AllCommunityModule]);


export default defineNuxtPlugin(nuxtApp => {
    nuxtApp.vueApp.component('AgGridVue', AgGridVue)
})
