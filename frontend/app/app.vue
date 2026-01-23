<template>
  <UApp>
    <div class="min-h-screen flex flex-col">
      <MyHeader />
      <PublicSidebar />
      <GlobalLoader />
      <GlobalSearch />
      <NuxtLayout>
        <main class="flex-grow">
          <NuxtPage />
        </main>
      </NuxtLayout>
      <MyFooter />
    </div>
  </UApp>
</template>
