<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Order;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Twig\Environment as TwigEnvironment;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class EmailService
{
    public function __construct(
        private MailerInterface $mailer,
        private TwigEnvironment $twig,
        private UrlGeneratorInterface $router,
        private UserRepository $userRepository,
        private string $fromEmail = 'no-reply@monsite.com',
        private string $fromName = 'MonSite'
    ) {}

    /**
     * Envoie un mail générique
     *
     * @param string $to      Destinataire
     * @param string $subject Sujet du mail
     * @param string $content Contenu en texte brut ou HTML
     * @param bool   $isHtml  Définit si le contenu est HTML
     *
     * @return bool
     */
    public function sendEmail(string $to, string $subject, string $content, bool $isHtml = false): bool
    {
        $email = (new Email())
            ->from(new Address($this->fromEmail, $this->fromName))
            ->to($to)
            ->subject($subject);

        if ($isHtml) {
            $email->html($content);
        } else {
            $email->text($content);
        }

        try {
            $this->mailer->send($email);
            return true;
        } catch (TransportExceptionInterface $e) {
            error_log('Erreur lors de l\'envoi du mail : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Exemple : Mail de bienvenue
     */
    public function sendWelcomeEmail(string $to, string $username): bool
    {
        $subject = 'Bienvenue sur MonSite 🎉';
        $content = "Bonjour $username,\n\nMerci de vous être inscrit sur MonSite !\n\nÀ bientôt.";
        return $this->sendEmail($to, $subject, $content);
    }

    /**
     * Exemple : Mail HTML
     */
    public function sendHtmlNotification(string $to, string $title, string $htmlContent): bool
    {
        return $this->sendEmail($to, $title, $htmlContent, true);
    }

    public function sendVerificationEmail(string $to, User $user, string $token): bool
    {
        $verificationUrl = $this->router->generate(
            'app_verify_email',
            ['token' => $token, "id"=> $user->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $htmlContent = $this->twig->render('emails/verification.html.twig', [
            'username' => $user->getFirstname(),
            'verificationUrl' => $verificationUrl
        ]);

        return $this->sendEmail($to, 'Confirmez votre email sur MonSite 🔒', $htmlContent, true);
    }

    public function sendOrderPaidEmail(Order $order): bool
    {
        $user = $order->getUser();

        if (!$user || !$user->getEmail()) {
            return false;
        }

        $totalAmount = $order->getTotal();
        $totalAmount = number_format($totalAmount/100, 2, ',', ' ');

        $htmlContent = $this->twig->render('emails/order_paid.html.twig', [
            'username'     => $user->getFirstname(),
            'orderId'  => $order->getId(),
            'orderDate'    => $order->getCreatedAt(),
            'totalAmount'  => $totalAmount
        ]);

        return $this->sendEmail(
            $user->getEmail(),
            'Nous avons bien reçu votre commande ✅',
            $htmlContent,
            true
        );
    }

    public function sendVendorNewOrderEmail(Order $order): bool 
    {
        $user = $order->getUser();
        $vendorUser = $this->userRepository->findOneVendor();

        if (!$vendorUser || !$user) {
            return false;
        }

        $totalAmount = $order->getTotal();
        $totalAmount = number_format($totalAmount / 100, 2, ',', ' ');

        $htmlContent = $this->twig->render('emails/vendor_new_order.html.twig', [
            'orderNumber'  => $order->getId(),
            'orderDate'    => $order->getCreatedAt(),
            'customerName' => sprintf(
                '%s %s',
                $user->getFirstname(),
                $user->getLastname()
            ),
            'totalAmount'  => $totalAmount,
        ]);

        return $this->sendEmail(
            $vendorUser->getEmail(),
            'Nouvelle commande en attente 🛒',
            $htmlContent,
            true
        );
    }

    public function sendOrderShippedEmail(Order $order): bool
    {
        $user = $order->getUser();

        if (!$user || !$user->getEmail()) {
            return false;
        }

        $totalAmount = $order->getTotal();
        $totalAmount = number_format($totalAmount/100, 2, ',', ' ');

        $htmlContent = $this->twig->render('emails/order_shipped.html.twig', [
            'username'     => $user->getFirstname(),
            'orderId'      => $order->getId(),
            'orderDate'    => $order->getCreatedAt(),
            'shippedDate'  => $order->getShippedAt(),
            'totalAmount'  => $totalAmount
        ]);

        return $this->sendEmail(
            $user->getEmail(),
            'Votre commande a été expédiée ! 🚚',
            $htmlContent,
            true
        );
    }

    public function sendOrderCancelledEmail(Order $order, ?string $cancellationReason = null): bool
    {
        $user = $order->getUser();

        if (!$user || !$user->getEmail()) {
            return false;
        }

        $totalAmount = $order->getTotal();
        $totalAmount = number_format($totalAmount/100, 2, ',', ' ');

        $htmlContent = $this->twig->render('emails/order_cancelled.html.twig', [
            'username'          => $user->getFirstname(),
            'orderId'           => $order->getId(),
            'orderDate'         => $order->getCreatedAt(),
            'cancelledDate'     => $order->getCancelledAt(),
            'totalAmount'       => $totalAmount,
            'cancellationReason' => $cancellationReason
        ]);

        return $this->sendEmail(
            $user->getEmail(),
            'Votre commande a été annulée ❌',
            $htmlContent,
            true
        );
    }
}
