<template>
  <div class="min-h-screen bg-neutral-50 text-neutral-900 selection:bg-neutral-900 selection:text-white">
    
    <!-- ============================================ -->
    <!-- 1. HERO BANNER : MOSAÏQUE ARTISTIQUE         -->
    <!-- ============================================ -->
    <section class="relative my-bg py-10 md:py-16 px-4 sm:px-6 lg:px-8 border-b border-neutral-200/80">
      <div class="max-w-6xl mx-auto">
        
        <!-- Grille Mosaïque Artistique 5x3 (Dynamique) -->
        <HomeMosaic :products="showcaseData?.mosaic" />

        <!-- Boutons d'action sous la mosaïque -->
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4 text-center">
          <NuxtLink
            to="/shop"
            class="inline-flex items-center gap-3 px-8 py-3.5 rounded-full bg-neutral-900 hover:bg-neutral-800 text-white font-semibold text-sm shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5"
          >
            <span>Explorer la boutique complète</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
          </NuxtLink>
          <a
            href="#selection"
            class="inline-flex items-center gap-2 px-6 py-3.5 rounded-full bg-white/80 hover:bg-white text-neutral-800 font-medium text-sm border border-neutral-300 shadow-xs transition-colors"
          >
            <span>Voir nos coups de cœur</span>
            <svg class="w-4 h-4 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </a>
        </div>

      </div>
    </section>

    <!-- ============================================ -->
    <!-- 2. BANDEAU DE RÉASSURANCE                    -->
    <!-- ============================================ -->
    <section class="border-b border-neutral-200 bg-white py-8">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <div class="flex items-center gap-4 p-4 rounded-xl hover:bg-neutral-50 transition-colors">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
              </svg>
            </div>
            <div>
              <h4 class="font-semibold text-neutral-900 text-sm">Livraison Soignée</h4>
              <p class="text-xs text-neutral-500">Expédition rapide et emballages protégés</p>
            </div>
          </div>

          <div class="flex items-center gap-4 p-4 rounded-xl hover:bg-neutral-50 transition-colors">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
              </svg>
            </div>
            <div>
              <h4 class="font-semibold text-neutral-900 text-sm">Paiement 100% Sécurisé</h4>
              <p class="text-xs text-neutral-500">Transactions chiffrées & certifiées</p>
            </div>
          </div>

          <div class="flex items-center gap-4 p-4 rounded-xl hover:bg-neutral-50 transition-colors">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
              </svg>
            </div>
            <div>
              <h4 class="font-semibold text-neutral-900 text-sm">Qualité Artisanale</h4>
              <p class="text-xs text-neutral-500">Tirages d'art et finitions d'exception</p>
            </div>
          </div>

          <div class="flex items-center gap-4 p-4 rounded-xl hover:bg-neutral-50 transition-colors">
            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center flex-shrink-0">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
              </svg>
            </div>
            <div>
              <h4 class="font-semibold text-neutral-900 text-sm">Retours Simples</h4>
              <p class="text-xs text-neutral-500">14 jours pour changer d'avis</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ============================================ -->
    <!-- 3. UNIVERS / CATÉGORIES                      -->
    <!-- ============================================ -->
    <section id="categories" class="py-16 lg:py-24">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
          <div>
            <span class="text-xs font-bold uppercase tracking-wider text-amber-600">Nos Univers</span>
            <h2 class="text-3xl sm:text-4xl font-bold text-neutral-900 mt-1">Explorez par catégorie</h2>
          </div>
          <NuxtLink to="/shop" class="inline-flex items-center gap-2 text-sm font-semibold text-neutral-700 hover:text-neutral-950 transition-colors">
            <span>Voir tout le catalogue</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </NuxtLink>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <!-- Carte Catégorie 1: Art -->
          <NuxtLink
            to="/result?category=art"
            class="group relative h-80 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-end p-8 bg-neutral-900 text-white"
          >
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent z-10"></div>
            <div class="absolute inset-0 bg-[radial-gradient(#d97706_1px,transparent_1px)] [background-size:16px_16px] opacity-25 group-hover:scale-110 transition-transform duration-700"></div>
            
            <div class="relative z-20 space-y-2">
              <span class="text-xs font-semibold uppercase tracking-widest text-amber-400">Univers Créatif</span>
              <h3 class="text-2xl font-bold">Art & Illustrations</h3>
              <p class="text-sm text-neutral-300 line-clamp-2">Tableaux graphiques, illustrations modernes et toiles audacieuses.</p>
              <div class="pt-2 inline-flex items-center gap-2 text-xs font-semibold text-white group-hover:text-amber-400 transition-colors">
                <span>Découvrir les œuvres</span>
                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </div>
            </div>
          </NuxtLink>

          <!-- Carte Catégorie 2: Photo -->
          <NuxtLink
            to="/result?category=photo"
            class="group relative h-80 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-end p-8 bg-neutral-900 text-white"
          >
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent z-10"></div>
            <div class="absolute inset-0 bg-[radial-gradient(#6366f1_1px,transparent_1px)] [background-size:16px_16px] opacity-25 group-hover:scale-110 transition-transform duration-700"></div>

            <div class="relative z-20 space-y-2">
              <span class="text-xs font-semibold uppercase tracking-widest text-indigo-400">Tirages Numérotés</span>
              <h3 class="text-2xl font-bold">Photographies</h3>
              <p class="text-sm text-neutral-300 line-clamp-2">Paysages captivants, scènes de vie urbaines et tirages haute définition.</p>
              <div class="pt-2 inline-flex items-center gap-2 text-xs font-semibold text-white group-hover:text-indigo-400 transition-colors">
                <span>Parcourir la galerie</span>
                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </div>
            </div>
          </NuxtLink>

          <!-- Carte Catégorie 3: Nouveautés -->
          <NuxtLink
            to="/result?sort=desc"
            class="group relative h-80 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-end p-8 bg-neutral-900 text-white"
          >
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent z-10"></div>
            <div class="absolute inset-0 bg-[radial-gradient(#10b981_1px,transparent_1px)] [background-size:16px_16px] opacity-25 group-hover:scale-110 transition-transform duration-700"></div>

            <div class="relative z-20 space-y-2">
              <span class="text-xs font-semibold uppercase tracking-widest text-emerald-400">Fraîchement Arrivé</span>
              <h3 class="text-2xl font-bold">Dernières Pièces</h3>
              <p class="text-sm text-neutral-300 line-clamp-2">Les nouveautés ajoutées récemment par nos créateurs partenaires.</p>
              <div class="pt-2 inline-flex items-center gap-2 text-xs font-semibold text-white group-hover:text-emerald-400 transition-colors">
                <span>Voir les nouveautés</span>
                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </div>
            </div>
          </NuxtLink>
        </div>
      </div>
    </section>

    <!-- ============================================ -->
    <!-- 4. VITRINE DE PRODUITS DYNAMIQUE             -->
    <!-- ============================================ -->
    <section id="selection" class="py-16 lg:py-24 bg-white border-y border-neutral-200">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Entête avec sélecteur d'onglets -->
        <div class="flex flex-col lg:flex-row lg:items-end justify-between mb-12 gap-6">
          <div>
            <span class="text-xs font-bold uppercase tracking-wider text-amber-600">Sélection Exclusive</span>
            <h2 class="text-3xl sm:text-4xl font-bold text-neutral-900 mt-1">Nos coups de cœur du moment</h2>
          </div>

          <!-- Onglets de filtre -->
          <div class="flex items-center gap-2 p-1.5 rounded-xl bg-neutral-100 border border-neutral-200 self-start">
            <button
              v-for="tab in tabs"
              :key="tab.key"
              @click="activeTab = tab.key"
              :class="[
                'px-4 py-2 text-xs sm:text-sm font-semibold rounded-lg transition-all duration-200 cursor-pointer',
                activeTab === tab.key
                  ? 'bg-white text-neutral-900 shadow-sm'
                  : 'text-neutral-600 hover:text-neutral-900'
              ]"
            >
              {{ tab.label }}
            </button>
          </div>
        </div>

        <!-- Grille des produits -->
        <div v-if="displayedProducts.length > 0" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
          <div
            v-for="product in displayedProducts"
            :key="product.id"
            class="group bg-neutral-50 border border-neutral-200/80 rounded-2xl overflow-hidden hover:shadow-xl hover:border-neutral-300 transition-all duration-300 flex flex-col"
          >
            <!-- Image & Badge -->
            <NuxtLink :to="`/product/${product.slug}`" class="relative aspect-square w-full overflow-hidden bg-neutral-200 block">
              <img
                v-if="product.mainImage?.path"
                :src="baseUrl + '/' + product.mainImage.path"
                :alt="product.title"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                loading="lazy"
              />
              <div v-else class="w-full h-full flex items-center justify-center text-neutral-400">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>

              <span
                v-if="product.stock > 0"
                class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded-md text-[10px] font-bold bg-white/90 backdrop-blur-sm text-neutral-800 shadow-xs"
              >
                En stock
              </span>
            </NuxtLink>

            <!-- Infos produit -->
            <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
              <div>
                <NuxtLink :to="`/product/${product.slug}`">
                  <h3 class="font-semibold text-neutral-900 text-sm group-hover:text-amber-600 transition-colors line-clamp-1">
                    {{ product.title }}
                  </h3>
                </NuxtLink>
                <p v-if="product.description" class="text-xs text-neutral-500 line-clamp-2 mt-1">
                  {{ product.description }}
                </p>
              </div>

              <div class="flex items-center justify-between pt-2 border-t border-neutral-200/60">
                <span class="text-base font-bold text-neutral-950">
                  {{ formatPrice(product.price) }}
                </span>
                <NuxtLink
                  :to="`/product/${product.slug}`"
                  class="p-2 rounded-lg bg-neutral-900 hover:bg-amber-500 text-white transition-colors"
                  aria-label="Voir le produit"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                  </svg>
                </NuxtLink>
              </div>
            </div>
          </div>
        </div>

        <!-- État vide ou chargement -->
        <div v-else class="text-center py-16 bg-neutral-50 rounded-2xl border border-dashed border-neutral-300">
          <p class="text-neutral-500 font-medium">Chargement des créations en cours...</p>
        </div>

        <!-- Bouton vers la boutique entière -->
        <div class="mt-14 text-center">
          <NuxtLink
            to="/shop"
            class="inline-flex items-center gap-3 px-8 py-3.5 rounded-xl bg-neutral-900 hover:bg-neutral-800 text-white font-medium text-sm transition-all"
          >
            <span>Accéder à l'ensemble du catalogue</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
          </NuxtLink>
        </div>
      </div>
    </section>

    <!-- ============================================ -->
    <!-- 5. NOTRE ENGAGEMENT / STORYTELLING          -->
    <!-- ============================================ -->
    <section class="py-16 lg:py-24 bg-neutral-100">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-neutral-900 rounded-3xl text-white p-8 sm:p-12 lg:p-16 overflow-hidden relative shadow-2xl">
          <div class="absolute -right-20 -bottom-20 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

          <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center relative z-10">
            <div class="space-y-6">
              <span class="text-xs font-bold uppercase tracking-widest text-amber-400">À propos de nous</span>
              <h2 class="text-3xl sm:text-4xl font-bold leading-snug">
                L'authenticité et le souci du détail au cœur de chaque création
              </h2>
              <p class="text-neutral-300 text-sm sm:text-base leading-relaxed">
                Notre mission est simple : connecter des créateurs de talent avec des passionnés en quête d'originalité. Chaque pièce proposée sur notre boutique est rigoureusement sélectionnée pour la qualité de ses finitions, son histoire et sa force d'évocation.
              </p>

              <div class="space-y-3 pt-2">
                <div class="flex items-center gap-3">
                  <div class="w-5 h-5 rounded-full bg-amber-400/20 text-amber-400 flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                  </div>
                  <span class="text-sm text-neutral-200">Soutien direct aux créateurs et photographes indépendants</span>
                </div>
                <div class="flex items-center gap-3">
                  <div class="w-5 h-5 rounded-full bg-amber-400/20 text-amber-400 flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                  </div>
                  <span class="text-sm text-neutral-200">Matériaux premium : papiers d'art, toiles et encres durables</span>
                </div>
                <div class="flex items-center gap-3">
                  <div class="w-5 h-5 rounded-full bg-amber-400/20 text-amber-400 flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                  </div>
                  <span class="text-sm text-neutral-200">Emballage anti-choc éco-responsable pour chaque commande</span>
                </div>
              </div>

              <div class="pt-4">
                <NuxtLink
                  to="/about"
                  class="inline-flex items-center gap-2 text-sm font-semibold text-amber-400 hover:text-amber-300 transition-colors"
                >
                  <span>En savoir plus sur notre démarche</span>
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                  </svg>
                </NuxtLink>
              </div>
            </div>

            <!-- Encadré citation / promesse -->
            <div class="bg-white/5 border border-white/10 rounded-2xl p-8 backdrop-blur-sm space-y-6">
              <svg class="w-10 h-10 text-amber-400/40" fill="currentColor" viewBox="0 0 24 24">
                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
              </svg>
              <p class="text-base sm:text-lg italic text-neutral-200 font-light leading-relaxed">
                "Nous croyons que chaque intérieur mérite une touche d'art singulière. Nous mettons un point d'honneur à soigner chaque étape, de la sélection à l'expédition."
              </p>
              <div class="pt-4 border-t border-white/10 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-amber-400 text-neutral-950 font-bold flex items-center justify-center text-sm">
                  MS
                </div>
                <div>
                  <p class="text-sm font-semibold text-white">L'équipe MonShop</p>
                  <p class="text-xs text-neutral-400">Fondateurs & Curateurs d'art</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>


  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { formatPrice } from '@/utils/formatPrice.js'

// Runtime config pour les URLs d'images
const config = useRuntimeConfig()
const baseUrl = config.public.NUXT_PUBLIC_API_BASE_URL || 'https://api.ton-domaine.local'

// Récupération des produits en vitrine et de la mosaïque
const { data: showcaseData } = await useShopShowcase()

// Gestion des onglets de sélection
const activeTab = ref('latest')

const tabs = [
  { key: 'latest', label: 'Nouveautés' },
  { key: 'art', label: 'Art & Toiles' },
  { key: 'photo', label: 'Photographies' }
]

const displayedProducts = computed(() => {
  if (!showcaseData.value) return []
  if (activeTab.value === 'art') return showcaseData.value.art || []
  if (activeTab.value === 'photo') return showcaseData.value.photo || []
  return showcaseData.value.latest || []
})

</script>

<style scoped>
.my-bg {
  background-image: url('/background.png');
  background-repeat: repeat;
  background-size: 800px;
}
</style>