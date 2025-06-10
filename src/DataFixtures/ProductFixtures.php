<?php

namespace App\DataFixtures;

use App\Domain\Product\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $products = [
            ['name' => 'Adjustable Dumbbells', 'price' => 79.99],
            ['name' => 'Yoga Mat', 'price' => 24.50],
            ['name' => 'Resistance Bands Set', 'price' => 19.99],
            ['name' => 'Kettlebell 12kg', 'price' => 35.00],
            ['name' => 'Foam Roller', 'price' => 18.75],
            ['name' => 'Treadmill', 'price' => 499.00],
            ['name' => 'Pull-Up Bar', 'price' => 27.99],
            ['name' => 'Fitness Tracker Watch', 'price' => 89.90],
            ['name' => 'Protein Powder 1kg', 'price' => 29.99],
            ['name' => 'Creatine Monohydrate', 'price' => 14.95],
            ['name' => 'BCAA Capsules', 'price' => 21.00],
            ['name' => 'Jump Rope', 'price' => 9.99],
            ['name' => 'Workout Gloves', 'price' => 15.99],
            ['name' => 'Medicine Ball 5kg', 'price' => 32.50],
            ['name' => 'Adjustable Bench', 'price' => 129.00],
            ['name' => 'Gym Bag', 'price' => 34.95],
            ['name' => 'Water Bottle 1L', 'price' => 12.49],
            ['name' => 'Ab Roller Wheel', 'price' => 17.80],
            ['name' => 'Weighted Vest 10kg', 'price' => 54.99],
            ['name' => 'Stepper Machine', 'price' => 145.00],
        ];

        foreach ($products as $input) {
            $product = new Product();
            $product->setName($input['name']);
            $product->setPrice($input['price']);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
