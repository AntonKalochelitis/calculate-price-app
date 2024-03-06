<?php

namespace App\Traits\DTO;

use Symfony\Component\Validator as Validator;

trait DTOTaxNumber
{
    #[Validator\Constraints\NotBlank(message: "taxNumber is required")]
    #[Validator\Constraints\Length(
        min: "11",
        max: "13",
        minMessage: "Your email must be at least {{ limit }} characters long",
        maxMessage: "Your email cannot be longer than {{ limit }} characters"
    )]
    #[Validator\Constraints\Regex(
        pattern: '/^(DE[0-9]{9}|IT[0-9]{11}|GR[0-9]{9}|FR[A-Z]{2}[0-9]{9})$/',
        message: 'Invalid tax number format'
    )]
    protected string $taxNumber;

    /**
     * @return string
     */
    public function getTaxNumber(): string
    {
        return $this->taxNumber;
    }

    /**
     * @param string $taxNumber
     * @return void
     */
    public function setTaxNumber(string $taxNumber): void
    {
        $this->taxNumber = $taxNumber;
    }
}