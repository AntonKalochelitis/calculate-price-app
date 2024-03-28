<?php

namespace App\Controller;

use App\Validator\DTOPurchase;
use App\Service\Purchase as ServicePurchase;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Psr\Log\LoggerInterface;

class PurchaseController extends AbstractController
{
    public function __construct(
        protected ServicePurchase    $servicePurchase,
        protected ValidatorInterface $validator,
        protected LoggerInterface    $logger
    )
    {
    }

    #[Route('/api/purchase', name: 'get_purchase', methods: ['POST'])]
    #[OA\Tag(name: 'Get purchase')]
    #[OA\RequestBody(
        content: new OA\MediaType(
            mediaType: "application/json",
            schema: new OA\Schema(
                ref: new Model(type: DTOPurchase::class)
            )
        )
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Purchase successful',
        content: new OA\JsonContent(
            example: '[{
	            "order_id": 1,
	            "product_name": "Iphone",
	            "product_amount": "100.00 EUR",
	            "coupon": "2.00 EUR",
	            "product_with_coupon": "98.00 EUR",
	            "tax": "22%",
	            "costing_tax": "21.56 EUR",
	            "costing_amount": "119.56 EUR"
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
            $dto = $this->servicePurchase->dto($request);

            $errors = $this->validator->validate($dto);
            if (count($errors) > 0) {
                return $this->json(
                    $errors,
                    Response::HTTP_BAD_REQUEST
                );
            }

            return $this->json($this->servicePurchase->pay(
                $dto->getProduct(),
                $dto->getTaxNumber(),
                $dto->getCouponCode(),
                $dto->getPaymentProcessor()
            ));
        } catch (\Exception $e) {
            $error = [
                $e->getFile(),
                $e->getCode(),
                $e->getMessage(),
            ];

            $this->logger->error(print_r($error, 1));
        }

        return $this->json(['error' => $error],
            Response::HTTP_BAD_REQUEST
        );
    }
}
