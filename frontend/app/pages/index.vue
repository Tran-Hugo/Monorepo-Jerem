<template>
  <div class="min-h-screen bg-neutral-50 text-neutral-900 selection:bg-neutral-900 selection:text-white">
    <!-- ============================================ -->
    <!-- 1. HERO SECTION                              -->
    <!-- ============================================ -->
    <section class="relative overflow-hidden bg-gradient-to-b from-neutral-900 via-neutral-800 to-neutral-900 text-white py-20 lg:py-28">
      <!-- Glows d'ambiance en arrière-plan -->
      <div class="absolute -top-40 -left-40 w-96 h-96 bg-amber-500/20 rounded-full blur-3xl pointer-events-none"></div>
      <div class="absolute top-1/2 -right-40 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>

      <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
          <!-- Texte et CTAs -->
          <div class="lg:col-span-7 space-y-8 text-center lg:text-left">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 border border-white/15 backdrop-blur-sm text-xs sm:text-sm font-medium text-amber-300">
              <span class="inline-block w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
              Nouvelle collection disponible
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight">
              Des créations d'exception pour <span class="bg-gradient-to-r from-amber-200 via-amber-400 to-yellow-300 bg-clip-text text-transparent">sublimer</span> votre quotidien
            </h1>

            <p class="text-lg sm:text-xl text-neutral-300 max-w-2xl mx-auto lg:mx-0 font-light leading-relaxed">
              Explorez notre sélection raffinée d'œuvres d'art, tirages photographiques et pièces exclusives conçues par des créateurs passionnés.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-2">
              <NuxtLink
                to="/shop"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-8 py-4 rounded-xl bg-amber-400 text-neutral-950 font-semibold text-base shadow-lg shadow-amber-400/20 hover:bg-amber-300 hover:scale-[1.02] active:scale-[0.98] transition-all duration-200"
              >
                <span>Découvrir la boutique</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
              </NuxtLink>

              <a
                href="#categories"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-4 rounded-xl bg-white/10 hover:bg-white/15 text-white font-medium text-base border border-white/15 backdrop-blur-sm transition-colors duration-200"
              >
                Explorer les catégories
              </a>
            </div>

            <!-- Micro-stats de réassurance sous les CTA -->
            <div class="pt-6 border-t border-white/10 grid grid-cols-3 gap-4 max-w-lg mx-auto lg:mx-0">
              <div>
                <p class="text-2xl font-bold text-white">4.9/5</p>
                <p class="text-xs text-neutral-400">Satisfaction clients</p>
              </div>
              <div>
                <p class="text-2xl font-bold text-white">+500</p>
                <p class="text-xs text-neutral-400">Pièces authentiques</p>
              </div>
              <div>
                <p class="text-2xl font-bold text-white">24/48h</p>
                <p class="text-xs text-neutral-400">Expédition suivie</p>
              </div>
            </div>
          </div>

          <!-- Visuel Hero (Carte produit vedette mise en scène) -->
          <div class="lg:col-span-5 flex justify-center">
            <div class="relative w-full max-w-md">
              <div class="absolute inset-0 bg-gradient-to-tr from-amber-500 to-indigo-500 rounded-3xl transform rotate-3 scale-105 opacity-30 blur-lg"></div>
              <div class="relative bg-neutral-900/90 border border-white/15 backdrop-blur-md rounded-3xl p-6 shadow-2xl space-y-4">
                <div class="relative aspect-square w-full rounded-2xl overflow-hidden bg-neutral-800">
                  <img
                    v-if="featuredHeroProduct?.mainImage?.path"
                    :src="baseUrl + '/' + featuredHeroProduct.mainImage.path"
                    :alt="featuredHeroProduct.title || 'Produit vedette'"
                    class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-700"
                  />
                  <div v-else class="w-full h-full flex items-center justify-center text-neutral-500">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                  </div>
                  <span class="absolute top-3 right-3 px-3 py-1 bg-black/60 backdrop-blur-md text-amber-300 text-xs font-semibold rounded-full border border-white/10">
                    Coup de cœur
                  </span>
                </div>

                <div class="flex items-center justify-between pt-2">
                  <div>
                    <h3 class="text-lg font-semibold text-white">
                      {{ featuredHeroProduct?.title || 'Création Exclusive' }}
                    </h3>
                    <p class="text-xs text-neutral-400">Édition limitée et signée</p>
                  </div>
                  <div class="text-right">
                    <p class="text-xl font-bold text-amber-400">
                      {{ featuredHeroProduct?.price ? formatPrice(featuredHeroProduct.price) : 'Découvrir' }}
                    </p>
                  </div>
                </div>

                <NuxtLink
                  :to="featuredHeroProduct?.slug ? `/product/${featuredHeroProduct.slug}` : '/shop'"
                  class="block w-full py-3 text-center text-sm font-semibold rounded-xl bg-white/10 hover:bg-white/20 text-white border border-white/15 transition-colors"
                >
                  Voir les détails de l'œuvre
                </NuxtLink>
              </div>
            </div>
          </div>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
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
    <!-- 3. CATÉGORIES EN VEDETTE                     -->
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

          <!-- Carte Catégorie 3: Nouveautés / Tout le magasin -->
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
    <section class="py-16 lg:py-24 bg-white border-y border-neutral-200">
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
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

    <!-- ============================================ -->
    <!-- 6. AVIS CLIENTS (SOCIAL PROOF)               -->
    <!-- ============================================ -->
    <section class="py-16 lg:py-24 bg-white">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14 space-y-3">
          <span class="text-xs font-bold uppercase tracking-wider text-amber-600">Témoignages</span>
          <h2 class="text-3xl sm:text-4xl font-bold text-neutral-900">Ce que disent nos clients</h2>
          <p class="text-neutral-500 text-sm">Des retours authentiques de passionnés qui nous font confiance.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div
            v-for="(review, index) in customerReviews"
            :key="index"
            class="p-6 rounded-2xl bg-neutral-50 border border-neutral-200/80 flex flex-col justify-between space-y-4 hover:shadow-md transition-shadow"
          >
            <!-- Étoiles -->
            <div class="flex items-center gap-1 text-amber-400">
              <svg v-for="star in 5" :key="star" class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
            </div>

            <p class="text-sm text-neutral-700 leading-relaxed italic">
              "{{ review.comment }}"
            </p>

            <div class="flex items-center gap-3 pt-2 border-t border-neutral-200">
              <div class="w-9 h-9 rounded-full bg-neutral-200 text-neutral-700 font-bold flex items-center justify-center text-xs">
                {{ review.initials }}
              </div>
              <div>
                <p class="text-sm font-semibold text-neutral-900">{{ review.name }}</p>
                <p class="text-xs text-neutral-500">{{ review.city }} · Achat vérifié</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ============================================ -->
    <!-- 7. NEWSLETTER / CLUB                         -->
    <!-- ============================================ -->
    <section class="py-16 bg-neutral-900 text-white relative overflow-hidden">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center max-w-2xl relative z-10 space-y-6">
        <span class="text-xs font-bold uppercase tracking-widest text-amber-400">Rejoignez le Club</span>
        <h2 class="text-3xl sm:text-4xl font-bold">10% de réduction sur votre première commande</h2>
        <p class="text-neutral-300 text-sm leading-relaxed">
          Inscrivez-vous pour recevoir nos sorties en avant-première, des invitations privées et des inspirations décorations exclusives.
        </p>

        <form @submit.prevent="handleNewsletter" class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto pt-2">
          <input
            v-model="newsletterEmail"
            type="email"
            placeholder="Entrez votre adresse email"
            required
            class="flex-1 px-4 py-3.5 rounded-xl bg-white/10 border border-white/20 text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm"
          />
          <button
            type="submit"
            class="px-6 py-3.5 rounded-xl bg-amber-400 hover:bg-amber-300 text-neutral-950 font-semibold text-sm transition-colors cursor-pointer"
          >
            S'inscrire
          </button>
        </form>
        <p class="text-xs text-neutral-500">Pas de spam. Vous pourrez vous désinscrire à tout moment.</p>
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

// Récupération des produits en vitrine
const { data: showcaseData } = await useShopShowcase()

// Produit vedette du Hero
const featuredHeroProduct = computed(() => {
  const latest = showcaseData.value?.latest || []
  return latest.length > 0 ? latest[0] : null
})

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

// Témoignages clients
const customerReviews = [
  {
    name: 'Sophie M.',
    city: 'Paris',
    initials: 'SM',
    comment: 'Tirage absolument somptueux ! L’emballage était ultra renforcé, la qualité du papier et la profondeur des couleurs ont largement dépassé mes attentes.'
  },
  {
    name: 'Alexandre D.',
    city: 'Lyon',
    initials: 'AD',
    comment: 'Commande reçue en 48h chrono. L’illustration est encore plus belle en vrai, elle donne une vraie ambiance à mon salon. Je recommande les yeux fermés.'
  },
  {
    name: 'Camille L.',
    city: 'Bordeaux',
    initials: 'CL',
    comment: 'Un service client exemplaire et des pièces d’art véritablement originales. C’est déjà mon 3ème achat sur cette boutique, toujours parfait !'
  }
]

// Newsletter
const newsletterEmail = ref('')
const toast = useToast()

const handleNewsletter = () => {
  if (!newsletterEmail.value) return
  toast.add({
    title: 'Bienvenue ! 🎉',
    description: 'Merci pour votre inscription ! Votre code de bienvenue vous a été envoyé.',
    color: 'success'
  })
  newsletterEmail.value = ''
}
</script>