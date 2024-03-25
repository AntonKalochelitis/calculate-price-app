<?php

namespace App\Service;

use App\Entity\TypeCoupon as EntityTypeCoupon;
use App\Repository\TypeCouponRepository;

class TypeCoupon
{
    public function __construct(
        protected TypeCouponRepository $typeCouponRepository
    )
    {
    }

    public function getObjectTypeCouponByTypeId(int $id): ?EntityTypeCoupon
    {
        $typeCoupon = $this->typeCouponRepository->findOneBy([
            'id' => $id,
        ]);

        return ((!empty($typeCoupon)) ? $typeCoupon : null);
    }

    /**
     * @param string $name
     * @return EntityTypeCoupon|null
     */
    public function getObjectTypeCouponByTypeName(string $name): ?EntityTypeCoupon
    {
        $typeCoupon = $this->typeCouponRepository->findOneBy([
            'name' => $name,
        ]);

        return ((!empty($typeCoupon)) ? $typeCoupon : null);
    }
}