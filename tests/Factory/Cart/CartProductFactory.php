<?php

namespace App\Tests\Factory\Cart;

use App\Domain\Cart\CartProduct;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

class CartProductFactory extends PersistentProxyObjectFactory
{
    public function __construct()
    {
    }

    public static function class(): string
    {
        return CartProduct::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'productId' => self::faker()->randomNumber(),
            'quantity' => 1,
        ];
    }

    protected function initialize(): static
    {
        return $this;
    }
}
