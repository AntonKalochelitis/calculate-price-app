<?php

namespace App\Service;

use App\Service\Tax as ServiceTax;
use App\Service\Coupon as ServiceCoupon;
use App\Entity\Product as EntityProduct;
use App\Repository\ProductRepository;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;

class PriceCalculator
{
    public function __construct(
        protected ServiceTax        $serviceTax,
        protected ServiceCoupon     $serviceCoupon,
        protected ProductRepository $productRepository
    )
    {
    }

    public function costing(
        int     $product,
        string  $taxNumber,
        ?string $couponCode
    )
    {
        /** @var EntityProduct $product */
        $product = $this->productRepository->find($product);
        if (empty($product)) {
            throw new \InvalidArgumentException('Product was not found');
        }

        $symbol = $product->getCurrency()->getSymbol();
        $productPrice = match ($symbol) {
            'EUR' => Money::EUR($product->getPrice()),
            'USD' => Money::USD($product->getPrice()),
        };

        $productPriceWithoutDiscount = $productPrice;
        if (!empty($couponCode)) {
            $productPrice = $this->serviceCoupon->addDiscountToPriceByCode($productPrice, $couponCode);
        }

        $percentTax = $this->serviceTax->getPercentByTaxNumber($taxNumber);
        $costingTax = $this->serviceTax->getAmountByTax($productPrice, $percentTax);
        $discountCoupon = $productPriceWithoutDiscount->subtract($productPrice);

        return [
            'product_name' => $product->getName(),
            'product_amount' => (new DecimalMoneyFormatter((new ISOCurrencies())))->format($productPriceWithoutDiscount) . ' ' . $symbol,
            'coupon' => (new DecimalMoneyFormatter((new ISOCurrencies())))->format($discountCoupon) . ' ' . $symbol,
            'product_with_coupon' => (new DecimalMoneyFormatter((new ISOCurrencies())))->format($productPrice) . ' ' . $symbol,
            'tax' => $percentTax . '%',
            'costing_tax' => (new DecimalMoneyFormatter((new ISOCurrencies())))->format($costingTax) . ' ' . $symbol,
            'costing_amount' => (new DecimalMoneyFormatter((new ISOCurrencies())))->format($productPrice->add($costingTax)) . ' ' . $symbol,
        ];
    }
}