<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\CartItem;
use InvalidArgumentException;
use App\Repository\ProductRepository;
use App\Repository\CartItemRepository;

class CartService
{

    public function __construct(
        private readonly CartItemRepository $cartItemRepository,
        private readonly ProductRepository $productRepository
    ) 
    {}

    /**
     * Adds an item to the user's cart.
     *
     * @param User $user The user who owns the cart.
     * @param int $productId The ID of the product to add.
     * @param int $quantity The quantity of the product to add.
     */
    public function addToCart(User $user, int $productId, int $quantity): void
    {
        $product = $this->productRepository->find($productId);
        if (!$product) {
            throw new InvalidArgumentException('Product not found.');
        }

        if ($product->getStock() === 0) {
            throw new InvalidArgumentException('Product out of stock.');
        }

        $cartItem = $this->cartItemRepository->findOneBy([
            'user' => $user,
            'product' => $product,
        ]);

        $existingQuantity = $cartItem ? $cartItem->getQuantity() : 0;
        $totalRequested = $existingQuantity + $quantity;

        $finalQuantity = min($totalRequested, $product->getStock());

        if ($cartItem) {
            $cartItem->setQuantity($finalQuantity);
        } else {
            $cartItem = new CartItem();
            $cartItem->setUser($user);
            $cartItem->setProduct($product);
            $cartItem->setQuantity($finalQuantity);
        }

        $this->cartItemRepository->save($cartItem, true);
    }


    /**
     * Retrieves all cart items for a specific user and adjusts quantities if needed.
     *
     * @param User $user The user whose cart items are to be retrieved.
     * @return array{products: CartItem[], total: float, messages: string[]}
     */
    public function getCartSummary(User $user): array
    {
        $cartItems = $this->cartItemRepository->findBy(['user' => $user]);
        $total = 0;
        $messages = [];

        foreach ($cartItems as $cartItem) {
            $product = $cartItem->getProduct();
            $availableStock = $product->getStock();

            // Produit hors stock → suppression du panier
            if ($availableStock === 0) {
                $messages[] = sprintf(
                    'Le produit "%s" a été retiré du panier car il est en rupture de stock.',
                    $product->getTitle()
                );
                $this->cartItemRepository->remove($cartItem, true);
                continue;
            }

            // Quantité > stock → ajustement
            if ($cartItem->getQuantity() > $availableStock) {
                $messages[] = sprintf(
                    'La quantité du produit "%s" a été réduite à %d (stock disponible).',
                    $product->getTitle(),
                    $availableStock
                );
                $cartItem->setQuantity($availableStock);
                $this->cartItemRepository->save($cartItem, true);
            }

            $total += $product->getPrice() * $cartItem->getQuantity();
        }

        return [
            'products' => $cartItems,
            'total' => $total,
            'messages' => $messages,
        ];
    }


    public function getItemCount(User $user): int
    {
        return $this->cartItemRepository->getTotalQuantity($user);
    }

    public function clearCart(User $user)
    {
        $this->cartItemRepository->clearCart($user);
    }
}