<?php

namespace App\Entity;

use App\Repository\CouponRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CouponRepository::class)]
class Coupon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $coupon = null;

    #[ORM\ManyToOne(inversedBy: 'couponList')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeCoupon $type = null;

    #[ORM\ManyToOne(inversedBy: 'couponList')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Currency $currency = null;

    #[ORM\Column]
    private ?int $value = null;

    #[ORM\Column(length: 1)]
    private ?int $status = null;

    #[ORM\OneToOne(mappedBy: 'coupon', cascade: ['persist', 'remove'])]
    private ?PurchaseOrder $purchaseOrder = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCoupon(): ?string
    {
        return $this->coupon;
    }

    public function setCoupon(string $coupon): static
    {
        $this->coupon = $coupon;

        return $this;
    }

    public function getType(): ?TypeCoupon
    {
        return $this->type;
    }

    public function setType(?TypeCoupon $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setCurrency(?Currency $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): static
    {
        $this->value = $value;

        return $this;
    }

    public const DEACTIVATED = 0;
    public const ACTIVE = 1;
    public const USED = 2;

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPurchaseOrder(): ?PurchaseOrder
    {
        return $this->purchaseOrder;
    }

    public function setPurchaseOrder(PurchaseOrder $purchaseOrder): static
    {
        // set the owning side of the relation if necessary
        if ($purchaseOrder->getCoupon() !== $this) {
            $purchaseOrder->setCoupon($this);
        }

        $this->purchaseOrder = $purchaseOrder;

        return $this;
    }
}
