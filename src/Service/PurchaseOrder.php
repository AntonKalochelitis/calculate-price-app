<?php

namespace App\Service;

use Carbon\Carbon;
use Money\Money;
use App\Entity\Coupon as EntityCoupon;
use App\Entity\Product as EntityProduct;
use App\Entity\PurchaseOrder as EntityPurchaseOrder;
use App\Repository\PaymentProcessorRepository;
use Doctrine\ORM\EntityManagerInterface;

class PurchaseOrder
{
    public function __construct(
        protected EntityManagerInterface     $entityManager,
        protected PaymentProcessorRepository $paymentProcessorRepository
    )
    {
    }

    public function saveOrder(
        EntityProduct       $product,
        string              $taxNumber,
        Money               $costingTax,
        ?EntityCoupon       $coupon,
        EntityPurchaseOrder $purchaseOrder
    ): ?EntityPurchaseOrder
    {
        $purchaseOrder->setProduct($product);
        $purchaseOrder->setTaxNumber($taxNumber);
        $purchaseOrder->setCurrency($product->getCurrency());
        $purchaseOrder->setCoupon($coupon);
        $purchaseOrder->setPrice($product->getPrice());
        $purchaseOrder->setTax($costingTax->getAmount());

        $date = \DateTimeImmutable::createFromMutable(Carbon::createFromTimestamp(time()));
        $purchaseOrder->setDate($date);

        $this->entityManager->persist($purchaseOrder);
        $this->entityManager->flush();

        return $purchaseOrder;
    }
}