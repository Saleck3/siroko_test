# Siroko interview technical test

This repo contains a coding exercise from the Siroko interview process

## Resume

This software should simulate an cart API and checkout system for an e-commerce

## Installation

After cloning the repo, copy and rename “example.env” to “.env” and set the variables values to the one that you prefer
Then run:

```
docker compose up -d
```

And navigate to [localhost](https://localhost/) (You should get a certificates warning, thats expected, just [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334))

On the browser you only gonna get the "Welcome to Symfony 7" placeholder but the app should be up and running at this point

## Running tests

If you have PHP and Symfony installed locally, you can run

```
symfony php vendor/bin/phpunit
```

If you don't you can run the tests directly in the container with

```
docker exec -it siroko_test-php sh -c "php vendor/bin/phpunit"
```

(Replace "siroko_test-php" with the name of the container that docker generated if it's different)

## Technologies and packages

-   Docker
-   PHP 8.3
-   MySQL 8
-   Symfony 7.3
-   Doctrine ORM
-   PHPUnit
-   Symfony’s Maker Bundle
-   Symfony’s Serializer
-   Zenstruck’s Foundry (Factories and database resets in tests)

Domain

- Product
    - Id (int)
    - Name (string)
    - Price (float)
- Cart
    - Id (int)
    - UserId (string)
    - Products (collection of CartProduct)
- CartProduct
    - Id (int)
    - ProductId (int) (soft reference to Product)
    - Quantity (int)
- Order
    - Id (int)
    - UserId (string)
    - Products (collection of OrderProduct)
- OrderProduct
    - Id (int)
    - ProductId (int) (soft reference to Product)
    - Quantity (int)
    - Price (float)

# Documentation

Use postman collection @ for methods calls and descriptions