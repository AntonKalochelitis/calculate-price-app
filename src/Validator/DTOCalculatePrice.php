<?php

namespace App\Validator;

use App\Traits\DTO\DTOCouponCode;
use App\Traits\DTO\DTOProduct;
use App\Traits\DTO\DTOTaxNumber;

class DTOCalculatePrice
{
    use DTOProduct;
    use DTOTaxNumber;
    use DTOCouponCode;
}