<?php

namespace App\Entity;

use App\Repository\TypeCouponRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeCouponRepository::class)]
class TypeCoupon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: Coupon::class, mappedBy: 'type', orphanRemoval: true)]
    private Collection $couponList;

    public function __construct()
    {
        $this->couponList = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public const FIXED_NAME = 'fixed';
    public const PERCENT_NAME = 'percent';

    public const FIXED = '1';
    public const PERCENT = '2';

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
            $couponList->setType($this);
        }

        return $this;
    }

    public function removeCouponList(Coupon $couponList): static
    {
        if ($this->couponList->removeElement($couponList)) {
            // set the owning side to null (unless already changed)
            if ($couponList->getType() === $this) {
                $couponList->setType(null);
            }
        }

        return $this;
    }
}
