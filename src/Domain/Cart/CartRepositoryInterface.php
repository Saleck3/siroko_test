<?php

namespace App\Domain\Cart;

interface CartRepositoryInterface
{

    public function save(Cart $cart): Cart;

    public function add(Cart $cart, bool $flush = false): Cart;

    public function findByUserId(string $userId);

}
