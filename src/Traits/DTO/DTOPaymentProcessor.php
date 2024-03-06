<?php

namespace App\Traits\DTO;

use Symfony\Component\Validator as Validator;

trait DTOPaymentProcessor
{
    #[Validator\Constraints\NotBlank(message: "paymentProcessor is required")]
    #[Validator\Constraints\Length(
        min: "5",
        max: "256",
        minMessage: "Your email must be at least {{ limit }} characters long",
        maxMessage: "Your email cannot be longer than {{ limit }} characters"
    )]
    protected string $paymentProcessor;

    /**
     * @return string
     */
    public function getPaymentProcessor(): string
    {
        return $this->paymentProcessor;
    }

    /**
     * @param string $paymentProcessor
     * @return void
     */
    public function setPaymentProcessor(string $paymentProcessor): void
    {
        $this->paymentProcessor = $paymentProcessor;
    }
}