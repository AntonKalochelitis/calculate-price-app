<?php

namespace App\Traits\DTO;

use Symfony\Component\Validator as Validator;

trait DTOProduct
{
    #[Validator\Constraints\NotBlank(message: "product is required")]
//    #[Validator\Constraints\Length(
//        min: "1",
//        max: "256",
//        minMessage: "Your email must be at least {{ limit }} characters long",
//        maxMessage: "Your email cannot be longer than {{ limit }} characters"
//    )]
    protected int $product;

    /**
     * @return int
     */
    public function getProduct(): int
    {
        return $this->product;
    }

    /**
     * @param int $product
     * @return void
     */
    public function setProduct(int $product): void
    {
        $this->product = $product;
    }
}