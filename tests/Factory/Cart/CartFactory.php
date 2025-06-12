<?php

namespace App\Tests\Factory\Cart;

use App\Domain\Cart\Cart;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

class CartFactory extends PersistentProxyObjectFactory
{
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Cart::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'userID' => self::faker()->text(255)
        ];
    }

    protected function initialize(): static
    {
        return $this;
    }
}
