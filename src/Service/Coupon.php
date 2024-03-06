<?php

namespace App\Service;

use App\Entity\Coupon as EntityCoupon;
use App\Entity\TypeCoupon as EntityTypeCoupon;
use App\Repository\CouponRepository;
use Doctrine\ORM\EntityManagerInterface;
use Money\Money;

class Coupon
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected CouponRepository       $couponRepository,
        protected Currency               $serviceCurrency,
        protected TypeCoupon             $serviceTypeCoupon
    )
    {
    }

    public function saveCoupon(
        string $typeName,
        string $code,
        string $currency,
        int    $value
    )
    {
        // Проверяем валидность типа
        self::couponCheckType($typeName);

        $entityCoupon = new EntityCoupon();
        $entityCoupon->setType($this->serviceTypeCoupon->getObjectTypeCouponByTypeName($typeName));
        $entityCoupon->setCoupon($code);
        $entityCoupon->setCurrency($this->serviceCurrency->getObjectCurrencyByCurrencySymbol($currency));
        $entityCoupon->setValue($value);
        $entityCoupon->setStatus(EntityCoupon::ACTIVE);

        // Заполните сущность Worker данными
        $this->entityManager->persist($entityCoupon);
        $this->entityManager->flush();

        return $entityCoupon;
    }

    /**
     *
     * $type == 'fixed'
     * $type == 'percent'
     *
     * @param int $value
     * @param int $count
     * @param string $type
     * @return array
     */
    public static function generate(
        int    $value,
        int    $count = 1,
        string $type = 'fixed'
    )
    {
        $couponList = [];

        //
        self::couponCheckType($type);

        for ($i = 0; $i < $count; $i++) {
            // Генерируем случайный код купона
            $couponCode = self::generateCouponCode(8) . '-' . self::generateCouponCode(8) . '-' . self::generateCouponCode(8);

            // Добавляем купон в массив
            $couponList = [
                'type' => $type,
                'code' => $couponCode,
                'value' => $value,
            ];
        }

        return $couponList;
    }

    protected static function generateCouponCode($length = 8)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $couponCode = '';

        for ($i = 0; $i < $length; $i++) {
            $couponCode .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $couponCode;
    }

    public static function couponCheckType(string $type): void
    {
        if (!($type === EntityTypeCoupon::FIXED_NAME || $type === EntityTypeCoupon::PERCENT_NAME)) {
            $backtrace = debug_backtrace();
            throw new \InvalidArgumentException('Invalid coupon type');
        }
    }

    public function addDiscountToPriceByCode(
        Money  $productPrice,
        string $couponCode
    ): Money
    {
        $discount = $this->getCouponByCode($couponCode);

        if (empty($discount)) {
            return $productPrice;
        }

        if (EntityTypeCoupon::PERCENT_NAME === $discount->getType()->getName()) {
            $discountPrice = $productPrice->multiply($discount->getValue())->divide(10000);
        } else {
            $discountPrice = match ($discount->getCurrency()->getSymbol()) {
                'EUR' => Money::EUR($discount->getValue()),
                'USD' => Money::USD($discount->getValue()),
            };
        }

        return $productPrice->subtract($discountPrice);
    }

    public function getCouponByCode(string $couponCode): ?EntityCoupon
    {
        /** @var EntityCoupon $discount */
        $discount = $this->couponRepository->findOneBy([
            'coupon' => $couponCode,
            'status' => EntityCoupon::ACTIVE
        ]);

        return $discount;
    }

    public function setCouponByCouponDeactivated(EntityCoupon $coupon): void
    {
        $coupon->setStatus(EntityCoupon::DEACTIVATED);

        $this->entityManager->persist($coupon);
        $this->entityManager->flush();
    }
}