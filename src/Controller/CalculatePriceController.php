<?php

namespace App\Controller;

use App\Validator\DTOCalculatePrice;
use App\Service\PriceCalculator;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CalculatePriceController extends AbstractController
{
    public function __construct(
        protected SerializerInterface $serializer,
        protected ValidatorInterface  $validator,
        protected PriceCalculator     $priceCalculator,
        protected LoggerInterface     $logger
    )
    {
    }

    #[Route('/api/calculate/price', name: 'get_calculate_price', methods: ['POST'])]
    #[OA\Tag(name: 'Calculate price')]
    #[OA\RequestBody(
        content: new OA\MediaType(
            mediaType: "application/json",
            schema: new OA\Schema(
                ref: new Model(type: DTOCalculatePrice::class)
            )
        )
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Purchase successful',
        content: new OA\JsonContent(
            example: '[{
	            "product_name": "Iphone",
	            "product_amount": "100.00 EUR",
	            "coupon": "2.00 EUR",
	            "product_with_coupon": "98.00 EUR",
	            "tax": "19%",
	            "costing_tax": "18.62 EUR",
	            "costing_amount": "116.62 EUR"
            }]')
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: 'Error',
        content: new OA\JsonContent(
            example: '{"error": string}'
        )
    )]
    public function index(Request $request): Response
    {
        try {
            /** @var DTOCalculatePrice $dto */
            $dto = $this->serializer->deserialize(
                $request->getContent(),
                DTOCalculatePrice::class,
                'json'
            );

            $errors = $this->validator->validate($dto);
            if (count($errors) > 0) {
                return $this->json(
                    $errors,
                    Response::HTTP_BAD_REQUEST
                );
            }

            return $this->json($this->priceCalculator->costing(
                $dto->getProduct(),
                $dto->getTaxNumber(),
                $dto->getCouponCode()
            ));
        } catch (\Exception $e) {
            $error = $e->getFile() . "\n" .
                $e->getCode() . "\n" .
                $e->getMessage() . "\n";

            $this->logger->error($error);
        }

        return $this->json(
            [
                'error' => $error
            ],
            Response::HTTP_BAD_REQUEST
        );
    }
}
