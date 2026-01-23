<template>
  <footer class="bg-gray-900 text-white py-10">
    <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-8">
      <!-- Logo & Description -->
      <div>
        <h2 class="text-xl font-semibold mb-3">MonShop</h2>
        <p class="text-sm text-gray-400">Votre destination pour les meilleurs produits au meilleur prix.</p>
      </div>

      <!-- Navigation -->
      <div>
        <h3 class="text-lg font-medium mb-2">Navigation</h3>
        <ul class="space-y-1 text-gray-400">
          <li><NuxtLink to="/" class="hover:text-white">Accueil</NuxtLink></li>
          <li><NuxtLink  class="hover:text-white">Boutique</NuxtLink></li>
          <li><NuxtLink to="/cart" class="hover:text-white">Panier</NuxtLink></li>
          <li><NuxtLink  class="hover:text-white">Contact</NuxtLink></li>
        </ul>
      </div>

      <!-- Informations -->
      <div>
        <h3 class="text-lg font-medium mb-2">Informations</h3>
        <ul class="space-y-1 text-gray-400">
          <li><NuxtLink  class="hover:text-white">Conditions générales</NuxtLink></li>
          <li><NuxtLink  class="hover:text-white">Confidentialité</NuxtLink></li>
          <li><NuxtLink  class="hover:text-white">Livraison & retours</NuxtLink></li>
        </ul>
      </div>

      <!-- Newsletter / Réseaux -->
      <div>
        <h3 class="text-lg font-medium mb-2">Newsletter</h3>
        <form @submit.prevent>
          <input
            type="email"
            placeholder="Votre e-mail"
            class="w-full px-3 py-2 mb-2 text-black rounded"
          />
          <button type="submit" class="w-full bg-yellow-500 text-white py-2 rounded hover:bg-primary-700 transition">
            S'inscrire
          </button>
        </form>
        <div class="mt-4 flex space-x-3">
          <a href="#" aria-label="Facebook" class="hover:text-primary">
            <Icon name="uil:facebook-f" />
          </a>
          <a href="#" aria-label="Twitter" class="hover:text-primary">
            <Icon name="uil:twitter" />
          </a>
          <a href="#" aria-label="Instagram" class="hover:text-primary">
            <Icon name="uil:instagram" />
          </a>
        </div>
      </div>
    </div>

    <div class="text-center text-sm text-gray-500 mt-10">
      &copy; {{ new Date().getFullYear() }} MonShop. Tous droits réservés.
    </div>
  </footer>
</template>
