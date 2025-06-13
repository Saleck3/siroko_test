<?php

namespace App\Domain\Order;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: OrderRepositoryInterface::class)]
#[ORM\Table(name: "user_orders")]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    /**
     * @Groups("order")
     */
    private ?int $id = null;

    /**
     * @var Collection<int, OrderProduct>
     */
    #[ORM\OneToMany(targetEntity: OrderProduct::class, mappedBy: 'order', cascade: ['persist'])]
    #[MaxDepth(1)]
    /**
     * @Groups("order")
     */
    private Collection $products;

    #[ORM\Column(length: 255)]
    /**
     * @Groups("order")
     */
    private ?string $userID = null;

    #[ORM\Column(nullable: true)]
    /**
     * @Groups("order")
     */
    private ?float $total = 0;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, OrderProduct>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(OrderProduct $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setOrder($this);
        }
        $this->total += $product->getPrice() * $product->getQuantity();

        return $this;
    }

    public function removeProduct(OrderProduct $product): static
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getOrder() === $this) {
                $product->setOrder(null);
            }
        }
        $this->total -= $product->getPrice() * $product->getQuantity();

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

    public function getTotal(): ?float
    {
        return $this->total;
    }

}
