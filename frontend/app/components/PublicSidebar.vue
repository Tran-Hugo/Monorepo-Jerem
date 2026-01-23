<template>
  <div>
    <!-- Sidebar -->
    <aside
      class="fixed inset-y-0 left-0 z-51 w-64 bg-white shadow p-6 transform transition-transform duration-300 md:hidden"
      :class="ui.publicSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    >
      <NuxtLink to="/" @click="ui.closePublicSidebar">
        <h2 class="text-xl font-bold mb-6">Menu</h2>
      </NuxtLink>
      <nav class="flex flex-col gap-3">
        <NuxtLink
          to="/"
          class="px-4 py-2 rounded hover:bg-gray-100"
          @click="ui.closePublicSidebar"
        >
          Accueil
        </NuxtLink>
        <NuxtLink
          to="/shop"
          class="px-4 py-2 rounded hover:bg-gray-100"
          @click="ui.closePublicSidebar"
        >
          Boutique
        </NuxtLink>
        <NuxtLink
          to="/about"
          class="px-4 py-2 rounded hover:bg-gray-100"
          @click="ui.closePublicSidebar"
        >
          À propos
        </NuxtLink>
        <NuxtLink v-if="auth?.user?.roles.includes('ROLE_ADMIN')" to="/admin" class="px-4 py-2 rounded hover:bg-gray-100" @click="ui.closePublicSidebar">Administration</NuxtLink>
        <!-- Add other public links as needed -->
      </nav>
    </aside>

    <!-- Overlay -->
    <div
      v-if="ui.publicSidebarOpen"
      class="fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden"
      @click="ui.closePublicSidebar"
    ></div>
  </div>
</template>

<script setup>
const ui = useUiStore()
const auth = useAuthStore()
</script>
