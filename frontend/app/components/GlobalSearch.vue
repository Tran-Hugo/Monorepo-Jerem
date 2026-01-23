<template>
  <Teleport to="body">
    <Transition name="fade">
      <div
        v-if="isOpen"
        class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm"
        @click.self="close"
        @keydown.esc="close"
      >
        <Transition name="slide-down">
          <div
            v-if="isOpen"
            class="absolute top-0 left-0 w-full bg-white shadow-xl border-b p-6"
          >
            <!-- Barre de recherche -->
            <div class="max-w-4xl mx-auto relative pb-16">
              <h1 class="m-auto font-bold text-2xl text-center mb-4">Rechercher</h1>
              <form @submit.prevent="submit" class="relative">
                <input
                  v-model="query"
                  type="text"
                  placeholder="Rechercher un produit..."
                  class="w-full border border-gray-300 rounded-lg px-4 py-3 pr-14 text-lg focus:outline-none focus:ring"
                />

                <!-- Bouton submit -->
                <UButton @click="submit" icon="material-symbols:search" color="neutral" variant="ghost" class="absolute right-2 top-1/2 -translate-y-1/2 bg-black text-white p-2 rounded-lg hover:bg-gray-800 transition active:scale-95 cursor-pointer"/>
              </form>
            </div>

            
          </div>
        </Transition>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
const store = useGlobalSearchStore()
const isOpen = ref(store.isOpen)
const query = ref('')
const results = ref([])

watch(() => store.isOpen, (val) => {
  isOpen.value = val
  if (val) {
    query.value = ''
    results.value = []
    document.body.style.overflow = 'hidden'
  } else {
    document.body.style.overflow = ''
  }
})

function close() {
  store.close()
}

function submit() {
  if (query.value.trim() === '') return
  close()
  const router = useRouter()
  router.push({ path: '/result', query: { q: query.value.trim() } })
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity .2s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.slide-down-enter-active {
  transition: transform .25s ease, opacity .25s ease;
}
.slide-down-leave-active {
  transition: transform .2s ease, opacity .2s ease;
}
.slide-down-enter-from,
.slide-down-leave-to {
  transform: translateY(-20px);
  opacity: 0;
}
</style>