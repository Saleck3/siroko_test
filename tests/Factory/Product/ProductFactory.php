<?php

namespace App\Tests\Factory\Product;

use App\Domain\Product\Product;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Product>
 */
final class ProductFactory extends PersistentProxyObjectFactory
{
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Product::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'name' => self::faker()->text(255),
            'price' => self::faker()->randomFloat(),
        ];
    }

    protected function initialize(): static
    {
        return $this;
    }
}
