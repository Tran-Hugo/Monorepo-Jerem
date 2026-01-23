<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-900 px-4">
    <div class="w-full max-w-md bg-white dark:bg-gray-800 shadow-md rounded-2xl p-8">
      <h1 class="text-2xl font-bold text-center text-gray-800 dark:text-white mb-6">Connexion</h1>
      
      <form @submit.prevent="onSubmit" class="space-y-4">
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
          <input
            v-model="email"
            type="email"
            id="email"
            class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:outline-none px-4 py-2"
            required
          />
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mot de passe</label>
          <input
            v-model="password"
            type="password"
            id="password"
            class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:outline-none px-4 py-2"
            required
          />
        </div>
        <div class="flex justify-center mt-6">
            <UButton type="submit" color="secondary" variant="solid" class="font-bold m-auto cursor-pointer">
              Se connecter
            </UButton>
        </div>
      </form>

      <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-4">
        Pas encore de compte ?
        <NuxtLink to="/register" class="text-blue-600 dark:text-blue-400 hover:underline">Créer un compte</NuxtLink>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const { $apiFetch } = useNuxtApp()
const email = ref('')
const password = ref('')
const router = useRouter()
const ui = useUiStore()
const cartStore = useCartStore()
const toast = useToast()
const auth = useAuthStore()

const onSubmit = async () => {
  try {
    ui.showLoader()
    await auth.login(email.value, password.value)

    cartStore.fetchCount()
    ui.hideLoader()
    router.push("/")
  } catch (error) {
    ui.hideLoader()

    toast.add({
      title: 'Connexion échouée',
      description: error?.data?.message || 'Une erreur est survenue',
      color: 'warning',
      icon: 'material-symbols:wifi-off',
    })
  }
}
</script>
