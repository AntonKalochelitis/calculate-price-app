<?php

namespace App\Controller;

use App\Validator\DTOPurchase;
use App\Entity\Product;
use App\Service\Purchase as ServicePurchase;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Psr\Log\LoggerInterface;

class PurchaseController extends AbstractController
{
    public function __construct(
        protected ServicePurchase     $servicePurchase,
        protected SerializerInterface $serializer,
        protected ValidatorInterface  $validator,
        protected LoggerInterface     $logger
    )
    {
    }

    #[Route('/api/purchase', name: 'get_purchase', methods: ['POST'])]
    #[OA\Tag(name: 'Get purchase')]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'The list of product became successful',
        content: new OA\JsonContent(
            type: "array",
            items: new OA\Items(ref: new Model(type: Product::class))
        )
    )]
    #[OA\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Workers not found',
        content: new OA\JsonContent(
            example: '{"error": string}'
        )
    )]
    public function index(Request $request): Response
    {
        try {
            /** @var DTOPurchase $dto */
            $dto = $this->serializer->deserialize(
                $request->getContent(),
                DTOPurchase::class,
                'json'
            );

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

        return $this->json(
            [
                'error' => $error
            ],
            Response::HTTP_BAD_REQUEST
        );
    }
}
