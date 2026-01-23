<template>
  <header class="bg-white shadow sticky top-0 z-50">
    <div class="mx-auto md:mx-[12rem] px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-24">
        <!-- Logo -->
        <div class="flex-shrink-0">
          <NuxtLink to="/" class="text-2xl font-bold text-gray-800">
            <NuxtImg src="/logo.png" width="90" height="90" />
          </NuxtLink>
        </div>

        <div class="flex-1"></div>

        <!-- Navigation -->
        <nav class="hidden md:flex space-x-8 items-center md:mr-50">
          <NuxtLink to="/" class="text-gray-700 font-medium hover:text-black hover:underline">Accueil</NuxtLink>
          <NuxtLink to="/shop" class="text-gray-700 font-medium hover:text-black hover:underline">Boutique</NuxtLink>
          <NuxtLink v-if="auth?.user?.roles.includes('ROLE_ADMIN')" to="/admin" class="text-gray-700 font-medium hover:text-black hover:underline">Administration</NuxtLink>
        </nav>

        <!-- Actions -->
        <div class="flex items-center space-x-4">
          <!-- Panier -->
          <NuxtLink to="/cart" class="relative">
            <svg class="w-6 h-6 text-gray-700 hover:text-black" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.3 5.2a1 1 0 001 1.3h12.6m-13.3-1H19m-6 0v.01"/>
            </svg>
            <ClientOnly>
              <span v-if="cartStore.count > 0" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full">
                {{ cartStore.count }}
              </span>
            </ClientOnly>
          </NuxtLink>

          <!-- Dropdown utilisateur -->
          <UDropdownMenu
            :items="dropdownItems"
            :content="{ align: 'start', side: 'bottom', sideOffset: 8 }"
            :ui="{ content: 'w-48' }"
          >
            <UButton icon="uil:user-circle" color="neutral" variant="ghost" class="cursor-pointer"/>
          </UDropdownMenu>
          <UButton @click="openSearch" icon="material-symbols:search" color="neutral" variant="ghost" class="cursor-pointer"/>

          <!-- Admin Menu Burger (mobile, admin only) -->
          <button v-if="isAdminRoute" @click="ui.toggleAdminSidebar" class="md:hidden p-2">
            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>

          <!-- Site Burger Menu (mobile, non-admin only) -->
          <button v-if="!isAdminRoute" @click="ui.togglePublicSidebar" class="md:hidden">
            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
          </button>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'

const ui = useUiStore()
const route = useRoute()
const isAdminRoute = computed(() => route.path.startsWith('/admin'))

const auth = useAuthStore()
const cartStore = useCartStore()
cartStore.fetchCount()

const dropdownItems = computed(() => {
  if (!auth.isAuthenticated) {
    return [
      { label: "Se connecter", icon: "uil:sign-in-alt", to: "/login" }
    ]
  }

  const items = [
    { label: "Mon compte", icon: "uil:user", to: "/account" }
  ]

  if (auth?.user?.roles?.includes('ROLE_ADMIN')) {
    items.push({ label: "Admin", icon: "uil:shield-check", to: "/admin" })
  }

  items.push({
    label: "Se déconnecter",
    icon: "uil:sign-out-alt",
    onSelect: async () => {
      await auth.logout()
    },
    class: 'cursor-pointer'
  })

  return items
})

const globalSearchStore = useGlobalSearchStore()

function openSearch() {
  globalSearchStore.open()
}
</script>
