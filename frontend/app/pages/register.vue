<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-900 px-4">
    <div class="w-full max-w-md bg-white dark:bg-gray-800 shadow-md rounded-2xl p-8">
      <h1 class="text-2xl font-bold text-center text-gray-800 dark:text-white mb-6">Inscription</h1>

      <form @submit.prevent="onSubmit" class="space-y-4">
        <!-- Email -->
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
          <input
            v-model="email"
            type="email"
            id="email"
            class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:outline-none px-4 py-2"
            required
          />
          <p v-if="errors.email" class="text-red-500 text-sm mt-1">{{ errors.email }}</p>
        </div>

        <!-- Prénom -->
        <div>
          <label for="firstname" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prénom</label>
          <input
            v-model="firstname"
            type="text"
            class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:outline-none px-4 py-2"
            required
          />
          <p v-if="errors.firstname" class="text-red-500 text-sm mt-1">{{ errors.firstname }}</p>
        </div>

        <!-- Nom -->
        <div>
          <label for="lastname" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom</label>
          <input
            v-model="lastname"
            type="text"
            class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:outline-none px-4 py-2"
            required
          />
          <p v-if="errors.lastname" class="text-red-500 text-sm mt-1">{{ errors.lastname }}</p>
        </div>

        <!-- Mot de passe -->
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mot de passe</label>
          <input
            v-model="password"
            type="password"
            class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:outline-none px-4 py-2"
            required
          />
          <p v-if="errors.password" class="text-red-500 text-sm mt-1">{{ errors.password }}</p>
        </div>

        <!-- Confirmation -->
        <div>
          <label for="confirmPassword" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirmez votre mot de passe</label>
          <input
            v-model="confirmPassword"
            type="password"
            class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:outline-none px-4 py-2"
            required
          />
        <p v-if="errors.confirmPassword" class="text-red-500 text-sm mt-1">{{ errors.confirmPassword }}</p>
        </div>

        <!-- Submit -->
        <div class="flex justify-center mt-6">
          <UButton type="submit" color="secondary" variant="solid" class="font-bold m-auto cursor-pointer">
            S'inscrire
          </UButton>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import useUserService from '~/services/UserApi'

const { $apiFetch } = useNuxtApp()
const userService = useUserService($apiFetch)
const ui = useUiStore()
const router = useRouter()

const email = ref('')
const firstname = ref('')
const lastname = ref('')
const password = ref('')
const confirmPassword = ref('')

// State pour les erreurs
const errors = ref({})

const validateForm = () => {
  errors.value = {}
  if (password.value !== confirmPassword.value) {
    errors.value.confirmPassword = 'Les mots de passe ne correspondent pas.'
    return false
  }
  return true
}

const onSubmit = async () => {
  try {
    if (!validateForm()) return

    ui.showLoader()

    await userService.register({
      email: email.value,
      firstname: firstname.value,
      lastname: lastname.value,
      password: password.value,
      confirmPassword: confirmPassword.value
    })

    ui.hideLoader()
    alert('Inscription réussie ! Vous pouvez maintenant vous connecter.')
    router.push('/login')
  } catch (error) {
    ui.hideLoader()

    if (error.data?.errors) {
      errors.value = error.data.errors
    } else {
      alert('Une erreur est survenue lors de l\'inscription. Veuillez réessayer.')
    }
  }
}
</script>
