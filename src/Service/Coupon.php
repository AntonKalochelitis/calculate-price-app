<?php

namespace App\Service;

use App\Entity\Coupon as EntityCoupon;
use App\Entity\TypeCoupon as EntityTypeCoupon;
use App\Repository\CouponRepository;
use App\Service\Coupon as ServiceCoupon;
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

    /**
     * @param string $typeName
     * @param string $code
     * @param string $currency
     * @param int $value
     * @return EntityCoupon
     */
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

    public function generateQuantity(
        string $type = 'fixed',
        int $quantity = 100,
        string $currency = 'EUR'
    )
    {
        $generateList = [];
        for ($i = 0; $i <= $quantity; $i++) {
            $generateList[$i] = ServiceCoupon::generate(mt_rand(1, 10) * 100, 100, $type);
            $this->saveCoupon(
                $generateList[$i]['type'],
                $generateList[$i]['code'],
                $currency,
                $generateList[$i]['value']
            );
        }

        return $generateList;
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

    /**
     * @param $length
     * @return string
     */
    protected static function generateCouponCode($length = 8)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $couponCode = '';

        for ($i = 0; $i < $length; $i++) {
            $couponCode .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $couponCode;
    }

    /**
     * @param string $type
     * @return void
     */
    public static function couponCheckType(string $type): void
    {
        if (!($type === EntityTypeCoupon::FIXED_NAME || $type === EntityTypeCoupon::PERCENT_NAME)) {
            $backtrace = debug_backtrace();
            throw new \InvalidArgumentException('Invalid coupon type');
        }
    }

    /**
     * @param Money $productPrice
     * @param string $couponCode
     * @return Money
     */
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

    /**
     * @param string $couponCode
     * @return EntityCoupon|null
     */
    public function getCouponByCode(string $couponCode): ?EntityCoupon
    {
        /** @var EntityCoupon $discount */
        $discount = $this->couponRepository->findOneBy([
            'coupon' => $couponCode,
            'status' => EntityCoupon::ACTIVE
        ]);

        return $discount;
    }

    /**
     * @param EntityCoupon $coupon
     * @return void
     */
    public function setCouponByCouponDeactivated(EntityCoupon $coupon): void
    {
        $coupon->setStatus(EntityCoupon::DEACTIVATED);

        $this->entityManager->persist($coupon);
        $this->entityManager->flush();
    }

    /**
     * @return EntityCoupon[]
     */
    public function getCouponList()
    {
        $returnCoupon = [];

        /** @var EntityCoupon[] $couponList */
        $couponList = $this->couponRepository->findBy([
            'status' => EntityCoupon::ACTIVE
        ]);

        foreach ($couponList as $coupon) {
            $returnCoupon[] = [
                'id' => $coupon->getId(),
                'type' => $this->serviceTypeCoupon->getObjectTypeCouponByTypeId($coupon->getType()->getId())->getName(),
                'currency' => $coupon->getCurrency()->getName(),
                'coupon' => $coupon->getCoupon(),
                'value' => $coupon->getValue()
            ];
        }

        return $returnCoupon;
    }
}