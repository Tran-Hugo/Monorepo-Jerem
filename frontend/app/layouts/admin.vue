<template>
  <div class="flex flex-1">
    <!-- Menu latéral -->
    <aside
      class="fixed inset-y-0 left-0 z-51 w-64 bg-white shadow p-6 transform transition-transform duration-300 md:relative md:translate-x-0 overflow-y-auto"
      :class="ui.adminSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    >
      <NuxtLink to="/admin" @click="ui.closeAdminSidebar">
        <h2 class="text-xl font-bold mb-6">Administration</h2>
      </NuxtLink>
      <nav class="flex flex-col gap-3">
        <NuxtLink
          to="/admin/commandes"
          class="px-4 py-2 rounded hover:bg-blue-100"
          :class="{ 'bg-blue-500 text-white font-semibold': $route.path.startsWith('/admin/commandes') }"
          @click="ui.closeAdminSidebar"
        >
          Commandes
        </NuxtLink>
        <NuxtLink
          to="/admin/produits"
          class="px-4 py-2 rounded hover:bg-blue-100"
          :class="{ 'bg-blue-500 text-white font-semibold': $route.path.startsWith('/admin/produits') }"
          @click="ui.closeAdminSidebar"
        >
          Produits
        </NuxtLink>
        <NuxtLink
          to="/admin/utilisateurs"
          class="px-4 py-2 rounded hover:bg-blue-100"
          :class="{ 'bg-blue-500 text-white font-semibold': $route.path.startsWith('/admin/utilisateurs') }"
          @click="ui.closeAdminSidebar"
        >
          Utilisateurs
        </NuxtLink>
        <NuxtLink
          to="/admin/categories"
          class="px-4 py-2 rounded hover:bg-blue-100"
          :class="{ 'bg-blue-500 text-white font-semibold': $route.path.startsWith('/admin/categories') }"
          @click="ui.closeAdminSidebar"
        >
          Catégories
        </NuxtLink>
        <NuxtLink
          to="/admin/expeditions"
          class="px-4 py-2 rounded hover:bg-blue-100"
          :class="{ 'bg-blue-500 text-white font-semibold': $route.path.startsWith('/admin/expeditions') }"
          @click="ui.closeAdminSidebar"
        >
          Méthodes d'expédition
        </NuxtLink>
      </nav>
    </aside>

    <!-- Overlay (mobile uniquement) -->
    <div
      v-if="ui.adminSidebarOpen"
      class="fixed inset-0 bg-black bg-opacity-50 z-20 md:hidden"
      @click="ui.closeAdminSidebar"
    ></div>

    <!-- Contenu principal -->
    <div class="flex-1">
      <main class="flex-1 p-4 sm:p-6">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
const ui = useUiStore()
</script>
