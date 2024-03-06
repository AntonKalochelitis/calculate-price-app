<?php

namespace App\Service;

use App\Repository\PaymentProcessorRepository;
use App\Entity\PaymentProcessor as EntityPaymentProcessor;

class PaymentProcessor
{
    public function __construct(
        protected PaymentProcessorRepository $paymentProcessorRepository
    )
    {
    }

    /**
     * @return EntityPaymentProcessor[]
     */
    public function getPaymentProcessorList(): array
    {
        return $this->paymentProcessorRepository->findBy([
            'status' => EntityPaymentProcessor::ACTIVE
        ]);
    }

    /**
     * @param string $paymentProcessorName
     * @param int $status
     * @return EntityPaymentProcessor|null
     */
    public function getPaymentProcessorByName(
        string $paymentProcessorName,
        int    $status = EntityPaymentProcessor::ACTIVE
    ): ?EntityPaymentProcessor
    {
        return $this->paymentProcessorRepository->findOneBy([
            'name' => $paymentProcessorName,
            'status' => $status
        ]);
    }

    public function getPaymentProcessorValid(string $paymentProcessorName): bool
    {
        if ($this->getPaymentProcessorByName($paymentProcessorName)) {
            return true;
        }

        return false;
    }
}