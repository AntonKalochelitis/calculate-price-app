<?php

namespace App\Validator;

use App\Traits\DTO\DTOCouponCode;
use App\Traits\DTO\DTOPaymentProcessor;
use App\Traits\DTO\DTOProduct;
use App\Traits\DTO\DTOTaxNumber;

class DTOPurchase
{
    use DTOProduct;
    use DTOTaxNumber;
    use DTOCouponCode;
    use DTOPaymentProcessor;
}