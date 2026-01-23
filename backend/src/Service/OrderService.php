<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Order;
use DateTimeImmutable;
use App\Service\CartService;
use App\Service\EmailService;
use App\Service\ProductService;
use App\Service\OrderItemService;
use App\Repository\OrderRepository;
use App\Repository\AddressRepository;

use App\Repository\ShippingMethodRepository;

class OrderService
{
    public function __construct(
        private AddressRepository $addressRepository,
        private ShippingMethodRepository $shippingMethodRepository,
        private CartService $cartService,
        private OrderItemService $orderItemService,
        private OrderRepository $orderRepository,
        private ProductService $productService,
        private EmailService $emailService
    ){}

    public function getOrder(array $data, User $user): Order
    {
        $order = $this->orderRepository->findOneBy([
            'user' => $user,
            'status' => 'pending'
        ]);

        if ($order) {
            $this->orderRepository->remove($order, true);
        }

        return $this->createOrder($data, $user);
    }


    public function createOrder(array $data, User $user): Order
    {
        $order = $this->initializeOrder($data, $user);
        $this->populateOrderWithCart($order, $user);
        $this->orderRepository->save($order, true);
        return $order;
    }

    private function initializeOrder(array $data, User $user): Order
    {
        $shippingAddress = $this->addressRepository->find($data["address"]["id"]);
        $billingAddress = $this->addressRepository->find($data["billingAddress"]["id"]);
        $shippingMethod = $this->shippingMethodRepository->find($data["shippingMethod"]["id"]);

        $order = new Order();
        $order->setShippingAddress($shippingAddress);
        $order->setBillingAddress($billingAddress);
        $order->setShippingMethod($shippingMethod);
        $order->setShippingPriceAtPurchase($shippingMethod->getPrice());
        $order->setUser($user);
        $order->setStatus("pending");
        $order->setCreatedAt(new DateTimeImmutable());

        return $order;
    }

    private function populateOrderWithCart(Order $order, User $user): void
    {
        $cart = $this->cartService->getCartSummary($user);

        foreach ($cart["products"] as $cartItem) {
            $orderItem = $this->orderItemService->createFromCartItem($cartItem);
            $order->addOrderItem($orderItem);
        }
    }

    public function getById($id)
    {
        return $this->orderRepository->find($id);
    }

    public function handlePaymentSuccess(Order $order)
    {
        if ($order->getStatus() === 'paid') {
            return;
        }
        $order->setStatus("paid");
        $this->orderRepository->save($order, true);
        $this->emailService->sendOrderPaidEmail($order);
        $this->emailService->sendVendorNewOrderEmail($order);
        
        $em = $this->orderRepository->getEntityManager();
        $em->beginTransaction();
        try {

            $this->cartService->clearCart($order->getUser(), false);
            $this->productService->decreaseStock($order, false);

            $em->flush();
            $em->commit();
        } catch (\Throwable $e) {
            $em->rollback();
            throw $e;
        }
    }

    public function shipOrder(Order $order): Order
    {
        if($order->getStatus() !== "paid") {
            throw new \Exception("Only paid orders can be shipped.");
        }
        $order->setStatus("shipped");
        $order->setShippedAt(new DateTimeImmutable());
        $this->orderRepository->save($order, true);
        $this->emailService->sendOrderShippedEmail($order);
        return $order;
    }

    public function cancelOrder(Order $order, ?string $cancellationReason = null): Order
    {
        if (!in_array($order->getStatus(), ['pending', 'paid'])) {
            throw new \Exception('Only pending or paid orders can be cancelled.');
        }

        $order->setStatus('cancelled');
        $order->setCancelledAt(new DateTimeImmutable());
        $this->orderRepository->save($order, true);
        $this->emailService->sendOrderCancelledEmail($order, $cancellationReason);

        return $order;
    }

}