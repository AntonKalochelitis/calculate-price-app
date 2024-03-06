<?php

namespace App\Traits\DTO;

use Symfony\Component\Validator as Validator;

trait DTOCouponCode
{
//    #[Validator\Constraints\NotBlank(message: "couponCode is required")]
    #[Validator\Constraints\Length(
        min: "3",
        max: "256",
        minMessage: "Your email must be at least {{ limit }} characters long",
        maxMessage: "Your email cannot be longer than {{ limit }} characters"
    )]
    protected string $couponCode;

    /**
     * @return string
     */
    public function getCouponCode(): ?string
    {
        return (!empty($this->couponCode)?$this->couponCode:null);
    }

    /**
     * @param string $couponCode
     * @return void
     */
    public function setCouponCode(string $couponCode): void
    {
        $this->couponCode = $couponCode;
    }
}