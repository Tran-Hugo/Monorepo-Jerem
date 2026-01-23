<?php

namespace App\Service;

use App\Entity\CartItem;
use App\Entity\OrderItem;

class OrderItemService
{
    public function createFromCartItem(CartItem $cartItem): OrderItem
    {
        $orderItem = new OrderItem();
        $orderItem->setQuantity($cartItem->getQuantity());
        $orderItem->setPriceAtPurchase($cartItem->getProduct()->getPrice());
        $orderItem->setProduct($cartItem->getProduct());

        return $orderItem;
    }
}