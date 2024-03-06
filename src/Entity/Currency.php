<?php

namespace App\Entity;

use App\Repository\CurrencyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurrencyRepository::class)]
class Currency
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 3)]
    private ?string $symbol = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: Coupon::class, mappedBy: 'currency', orphanRemoval: true)]
    private Collection $couponList;

    #[ORM\OneToMany(targetEntity: PaymentProcessor::class, mappedBy: 'currency', orphanRemoval: true)]
    private Collection $paymentProcessorList;

    #[ORM\OneToMany(targetEntity: PurchaseOrder::class, mappedBy: 'currency')]
    private Collection $purchaseOrderList;

    public function __construct()
    {
        $this->couponList = new ArrayCollection();
        $this->paymentProcessorList = new ArrayCollection();
        $this->purchaseOrderList = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): static
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Coupon>
     */
    public function getCouponList(): Collection
    {
        return $this->couponList;
    }

    public function addCouponList(Coupon $couponList): static
    {
        if (!$this->couponList->contains($couponList)) {
            $this->couponList->add($couponList);
            $couponList->setCurrency($this);
        }

        return $this;
    }

    public function removeCouponList(Coupon $couponList): static
    {
        if ($this->couponList->removeElement($couponList)) {
            // set the owning side to null (unless already changed)
            if ($couponList->getCurrency() === $this) {
                $couponList->setCurrency(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PaymentProcessor>
     */
    public function getPaymentProcessorList(): Collection
    {
        return $this->paymentProcessorList;
    }

    public function addPaymentProcessorList(PaymentProcessor $paymentProcessorList): static
    {
        if (!$this->paymentProcessorList->contains($paymentProcessorList)) {
            $this->paymentProcessorList->add($paymentProcessorList);
            $paymentProcessorList->setCurrency($this);
        }

        return $this;
    }

    public function removePaymentProcessorList(PaymentProcessor $paymentProcessorList): static
    {
        if ($this->paymentProcessorList->removeElement($paymentProcessorList)) {
            // set the owning side to null (unless already changed)
            if ($paymentProcessorList->getCurrency() === $this) {
                $paymentProcessorList->setCurrency(null);
            }
        }

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
            $purchaseOrderList->setCurrency($this);
        }

        return $this;
    }

    public function removePurchaseOrderList(PurchaseOrder $purchaseOrderList): static
    {
        if ($this->purchaseOrderList->removeElement($purchaseOrderList)) {
            // set the owning side to null (unless already changed)
            if ($purchaseOrderList->getCurrency() === $this) {
                $purchaseOrderList->setCurrency(null);
            }
        }

        return $this;
    }
}
