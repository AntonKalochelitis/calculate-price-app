<?php

namespace App\Controller;

use App\Service\Coupon as ServiceCoupon;
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

    #[Route('/api/coupon/list', name: 'get_coupon', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('coupon/index.html.twig', [
            'controller_name' => 'CouponController',
        ]);
    }

    #[Route('/api/coupon/generate', name: 'get_coupon', methods: ['GET'])]
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
