<?php

namespace App\Service;

use App\Entity\Currency as EntityCurrency;
use App\Repository\CurrencyRepository;

class Currency
{
    public function __construct(
        protected CurrencyRepository $currencyRepository
    )
    {
    }

    public function getObjectCurrencyByCurrencySymbol(string $symbol): ?EntityCurrency
    {
        $currency = $this->currencyRepository->findOneBy([
            'symbol' => $symbol
        ]);

        return ((!empty($currency)) ? $currency : null);
    }
}