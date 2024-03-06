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