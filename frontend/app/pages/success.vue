<template>
  <div class="success-page">
    <h1>Merci pour votre commande !</h1>
    <p>Votre paiement a été effectué avec succès.</p>
    <p>Vous allez bientôt recevoir un email de confirmation de votre commande. <br>
       Pensez à vérifier vos spams si vous ne le voyez pas dans votre boîte de réception.</p>
    <NuxtLink to="/">Retour à l'accueil</NuxtLink>
  </div>
</template>

<script setup>
// const route = useRoute()
// const orderId = route.query.orderID
</script>

<style scoped>
.success-page {
  text-align: center;
  padding: 4rem 2rem;
}
.success-page h1 {
  font-size: 2rem;
  margin-bottom: 1rem;
}
.success-page p {
  margin-bottom: 2rem;
}
</style>
