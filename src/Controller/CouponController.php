<?php

namespace App\Controller;

use App\Service\Coupon as ServiceCoupon;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CouponController extends AbstractController
{
    public function __construct(
        protected ServiceCoupon $serviceCoupon
    )
    {
    }

    #[Route('/api/coupon/list', name: 'get_coupon_list', methods: ['GET'])]
    #[OA\Tag(name: 'Get coupon list')]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'The coupon list successful',
        content: new OA\JsonContent(
            example: '[{
		        "id": 2,
		        "currency": "Euro",
		        "coupon": "MQ6HGQD1-B8E54CWG-8LG8AB9C",
		        "value": 900
            }, {
		        "id": 3,
		        "currency": "Euro",
		        "coupon": "THVRSA3C-0RRV9TD9-0OCA20P9",
		        "value": 300
	        }]')
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: 'Error',
        content: new OA\JsonContent(
            example: '{"error": string}'
        )
    )]
    public function index(): Response
    {
        return $this->json($this->serviceCoupon->getCouponList());
    }

    #[Route('/api/coupon/generate', name: 'get_generate_coupon_list', methods: ['GET'])]
    #[OA\Tag(name: 'Get coupon list')]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'The coupon list successful',
        content: new OA\JsonContent(
            example: '[{
		        "type": "fixed",
		        "code": "20CMYFUW-0MEB9SMV-4V7CSIU0",
		        "value": 200
	        }, {
		        "type": "fixed",
		        "code": "MQ6HGQD1-B8E54CWG-8LG8AB9C",
		        "value": 900
	        }]')
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: 'Error',
        content: new OA\JsonContent(
            example: '{"error": string}'
        )
    )]
    public function generate()
    {
        for ($i = 0; $i <= 100; $i++) {
            $generateList1[$i] = ServiceCoupon::generate(mt_rand(1, 10) * 100, 100, 'fixed');
            $this->serviceCoupon->saveCoupon(
                $generateList1[$i]['type'],
                $generateList1[$i]['code'],
                'EUR',
                $generateList1[$i]['value']
            );
        }

        for ($i = 0; $i <= 100; $i++) {
            $generateList2[$i] = ServiceCoupon::generate(mt_rand(1, 100) * 100, 100, 'percent');
            $this->serviceCoupon->saveCoupon(
                $generateList2[$i]['type'],
                $generateList2[$i]['code'],
                'EUR',
                $generateList2[$i]['value']
            );
        }

        return $this->json(array_merge($generateList1, $generateList2));
    }
}
