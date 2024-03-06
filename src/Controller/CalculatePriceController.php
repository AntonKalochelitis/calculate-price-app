<?php

namespace App\Controller;

use App\Validator\DTOCalculatePrice;
use App\Service\PriceCalculator;
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
    #[OA\Tag(name: 'Get calculate price')]
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
