<?php

namespace App\Service;

use App\Repository\TaxRepository;
use Money\Money;

class Tax
{
    public function __construct(
        protected TaxRepository $taxRepository
    )
    {
    }

    public function getPercentByTaxNumber(
        string $taxNumber
    ): int
    {
        $tax = $this->taxRepository->findOneBy([
            'code' => $this->getCountryCodeByTaxNumber($taxNumber)
        ]);

        return $tax->getValue();
    }

    public function getAmountByTax(
        Money $money,
        int $percentTax
    ): Money
    {
        return $this->getAmountByTypeTaxPercent($money, $percentTax);
    }

    public function getAmountByTypeTaxPercent(Money $money, int $value): Money
    {
        return $money->multiply($value)->divide(100);
    }

    public function getCountryCodeByTaxNumber($taxNumber): string
    {
        return substr($taxNumber, 0, 2);
    }
}
