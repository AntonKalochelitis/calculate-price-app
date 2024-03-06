<?php

namespace App\Service;

use App\Entity\Product as EntityProduct;
use App\Entity\PurchaseOrder as EntityPurchaseOrder;
use App\Repository\ProductRepository;
use App\Service\Coupon as ServiceCoupon;
use App\Service\Tax as ServiceTax;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;

class Purchase
{
    public function __construct(
        protected ServiceTax        $serviceTax,
        protected ServiceCoupon     $serviceCoupon,
        protected PurchaseOrder     $purchaseOrder,
        protected ProductRepository $productRepository,
        protected PaymentProcessor  $paymentProcessor
    )
    {
    }

    public function pay(
        int     $product,
        string  $taxNumber,
        ?string $couponCode,
        string  $paymentProcessor
    ): array
    {
        /** @var EntityProduct $product */
        $product = $this->productRepository->find($product);
        if (empty($product)) {
            throw new \InvalidArgumentException('Product was not found');
        }

        if (!$this->paymentProcessor->getPaymentProcessorValid($paymentProcessor)) {
            throw new \InvalidArgumentException('Payment Processor was not found');
        }

        $symbol = $product->getCurrency()->getSymbol();
        $productPrice = match ($symbol) {
            'EUR' => Money::EUR($product->getPrice()),
            'USD' => Money::USD($product->getPrice()),
        };

        $productPriceWithoutDiscount = $productPrice;
        if (!empty($couponCode)) {
            $coupon = $this->serviceCoupon->getCouponByCode($couponCode);
            $productPrice = $this->serviceCoupon->addDiscountToPriceByCode($productPrice, $couponCode);
        }

        $percentTax = $this->serviceTax->getPercentByTaxNumber($taxNumber);
        $costingTax = $this->serviceTax->getAmountByTax($productPrice, $percentTax);
        $discountCoupon = $productPriceWithoutDiscount->subtract($productPrice);

        if ($order = $this->purchaseOrder->saveOrder(
            $product,
            $costingTax,
            $coupon ?? null,
            (new EntityPurchaseOrder())
        )) {
            if (!empty($coupon)) {
                $this->serviceCoupon->setCouponByCouponDeactivated($coupon);
            }
        }

        return [
            'order_id' => $order->getId(),
            'product_name' => $order->getProduct()->getName(),
            'product_amount' => (new DecimalMoneyFormatter((new ISOCurrencies())))->format($productPriceWithoutDiscount) . ' ' . $symbol,
            'coupon' => (new DecimalMoneyFormatter((new ISOCurrencies())))->format($discountCoupon) . ' ' . $symbol,
            'product_with_coupon' => (new DecimalMoneyFormatter((new ISOCurrencies())))->format($productPrice) . ' ' . $symbol,
            'tax' => $percentTax . '%',
            'costing_tax' => (new DecimalMoneyFormatter((new ISOCurrencies())))->format($costingTax) . ' ' . $symbol,
            'costing_amount' => (new DecimalMoneyFormatter((new ISOCurrencies())))->format($productPrice->add($costingTax)) . ' ' . $symbol,
        ];
    }
}