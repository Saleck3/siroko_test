<?php

namespace App\Domain\Cart;

use App\Infrastructure\Cart\DoctrineCartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DoctrineCartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, CartProduct>
     */
    #[ORM\OneToMany(targetEntity: CartProduct::class, mappedBy: 'cart', cascade: ['persist'])]
    private Collection $products;

    #[ORM\Column(length: 255)]
    private ?string $userID = null;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, CartProduct>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(CartProduct $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setCart($this);
        }

        return $this;
    }

    public function removeProduct(CartProduct $product): static
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getCart() === $this) {
                $product->setCart(null);
            }
        }

        return $this;
    }

    public function getUserID(): ?string
    {
        return $this->userID;
    }

    public function setUserID(string $userID): static
    {
        $this->userID = $userID;

        return $this;
    }
}
