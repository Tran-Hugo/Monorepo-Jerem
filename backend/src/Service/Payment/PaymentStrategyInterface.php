<?php
namespace App\Service\Payment;

use App\Entity\Order;

interface PaymentStrategyInterface
{
    /**
     * Retourne le nom de la stratégie (ex: "paypal", "stripe").
     */
    public function getName(): string;

    /**
     * Crée une session de paiement (checkout) et retourne l'URL vers laquelle rediriger l'utilisateur.
     *
     * @param array $orderData [
     *     'total' => string (ex: "19.99"),
     *     'currency' => string (ex: "EUR"),
     *     'description' => string,
     *     'success_url' => string (URL de redirection après succès),
     *     'cancel_url' => string (URL de redirection après annulation),
     * ]
     *
     * @return string URL de redirection vers le fournisseur de paiement
     */
    public function createCheckoutSession(Order $orderData): array;

    // /**
    //  * (Optionnel) Gère la validation ou le traitement d’un paiement une fois terminé.
    //  * Par exemple : confirmation après retour, enregistrement dans la BDD, etc.
    //  */
    // public function handleWebhook(array $payload): void;
}
