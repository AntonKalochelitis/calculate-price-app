<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\ManyToOne(targetEntity: Currency::class)]
    #[ORM\JoinColumn(name: 'currency_id', referencedColumnName: 'id')]
    private ?Currency $currency = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\OneToMany(targetEntity: PurchaseOrder::class, mappedBy: 'product', orphanRemoval: true)]
    private Collection $purchaseOrderList;

    public function __construct()
    {
        $this->purchaseOrderList = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Currency|null
     */
    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    /**
     * @param Currency|null $currency
     * @return void
     */
    public function setCurrency(?Currency $currency): void
    {
        $this->currency = $currency;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, PurchaseOrder>
     */
    public function getPurchaseOrderList(): Collection
    {
        return $this->purchaseOrderList;
    }

    public function addPurchaseOrderList(PurchaseOrder $purchaseOrderList): static
    {
        if (!$this->purchaseOrderList->contains($purchaseOrderList)) {
            $this->purchaseOrderList->add($purchaseOrderList);
            $purchaseOrderList->setProduct($this);
        }

        return $this;
    }

    public function removePurchaseOrderList(PurchaseOrder $purchaseOrderList): static
    {
        if ($this->purchaseOrderList->removeElement($purchaseOrderList)) {
            // set the owning side to null (unless already changed)
            if ($purchaseOrderList->getProduct() === $this) {
                $purchaseOrderList->setProduct(null);
            }
        }

        return $this;
    }
}
