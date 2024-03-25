<?php

namespace App\Tests;

use App\Repository\CouponRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 */
class ApiRESTCheckerTest extends WebTestCase
{
    protected array $couponList = [];

    protected function getCoupon(string $typeName): array
    {
        foreach ($this->couponList as $key => $coupon) {
            if ((string)$coupon['type'] === $typeName) {
                unset($this->couponList[$key]);

                return $coupon;
            }
        }

        return [];
    }

    protected function testApiDoc(KernelBrowser $client): void
    {
        $response = $client->request(Request::METHOD_GET, '/api/doc');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    protected function testApiCouponGenerate(KernelBrowser $client): void
    {
        $response = $client->request(Request::METHOD_GET, '/api/coupon/generate');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    protected function testApiCouponList(KernelBrowser $client): void
    {
        $response = $client->request(Request::METHOD_GET, '/api/coupon/list');

        $this->couponList = json_decode(
            $client->getResponse()->getContent(),
            true
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    protected function apiCalculatePrice(
        KernelBrowser $client,
        string        $taxNumber,
        string        $typeName = ''
    )
    {
        $coupon = $this->getCoupon($typeName);

        $json = [
            "product" => 1,
            "taxNumber" => $taxNumber
        ];
        if (!empty($coupon)) {
            $json = array_merge($json, ["couponCode" => $coupon['coupon']]);
        }

        $headers = [
            'Content-Type' => 'application/json'
        ];

        $client->request(
            Request::METHOD_POST,
            '/api/calculate/price',
            ['headers' => $headers],
            [],
            [],
            json_encode($json, JSON_UNESCAPED_UNICODE)
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $jsonResponse = json_decode(
            $client->getResponse()->getContent(),
            true
        );

        $this->assertArrayHasKey('product_name', $jsonResponse, 'The response should contain an "product_name" field');
        $this->assertArrayHasKey('product_amount', $jsonResponse, 'The response should contain an "product_amount" field');
        $this->assertArrayHasKey('coupon', $jsonResponse, 'The response should contain an "coupon" field');
        $this->assertArrayHasKey('product_with_coupon', $jsonResponse, 'The response should contain an "product_with_coupon" field');
        $this->assertArrayHasKey('tax', $jsonResponse, 'The response should contain an "tax" field');
        $this->assertArrayHasKey('costing_tax', $jsonResponse, 'The response should contain an "costing_tax" field');
        $this->assertArrayHasKey('costing_amount', $jsonResponse, 'The response should contain an "costing_amount" field');
    }

    public function apiPurchase(
        KernelBrowser $client,
        string        $taxNumber,
        string        $typeName = ''
    ): void
    {
        $coupon = $this->getCoupon($typeName);

        $json = [
            "product" => 1,
            "taxNumber" => $taxNumber,
            "paymentProcessor" => "paypal"
        ];
        if (!empty($coupon)) {
            $json = array_merge($json, ["couponCode" => $coupon['coupon']]);
        }

        $headers = [
            'Content-Type' => 'application/json'
        ];

        $client->request(
            Request::METHOD_POST,
            '/api/purchase',
            ['headers' => $headers],
            [],
            [],
            json_encode($json, JSON_UNESCAPED_UNICODE)
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $jsonResponse = json_decode(
            $client->getResponse()->getContent(),
            true
        );

        $this->assertArrayHasKey('order_id', $jsonResponse, 'The response should contain an "order_id" field');
        $this->assertArrayHasKey('product_name', $jsonResponse, 'The response should contain an "product_name" field');
        $this->assertArrayHasKey('product_amount', $jsonResponse, 'The response should contain an "product_amount" field');
        $this->assertArrayHasKey('coupon', $jsonResponse, 'The response should contain an "coupon" field');
        $this->assertArrayHasKey('product_with_coupon', $jsonResponse, 'The response should contain an "product_with_coupon" field');
        $this->assertArrayHasKey('tax', $jsonResponse, 'The response should contain an "tax" field');
        $this->assertArrayHasKey('costing_tax', $jsonResponse, 'The response should contain an "costing_tax" field');
        $this->assertArrayHasKey('costing_amount', $jsonResponse, 'The response should contain an "costing_amount" field');
    }

    public function testDefault(): void
    {
        $client = static::createClient();

        $this->testApiDoc($client);
        $this->testApiCouponGenerate($client);
        $this->testApiCouponList($client);

        $this->apiCalculatePrice($client, 'DE123456789', 'fixed');
        $this->apiCalculatePrice($client, 'DE123456789', 'percent');
        $this->apiCalculatePrice($client, 'DE123456789');
        $this->apiCalculatePrice($client, 'IT12345678900', 'fixed');
        $this->apiCalculatePrice($client, 'IT12345678900', 'percent');
        $this->apiCalculatePrice($client, 'IT12345678900');
        $this->apiCalculatePrice($client, 'GR123456789', 'fixed');
        $this->apiCalculatePrice($client, 'GR123456789', 'percent');
        $this->apiCalculatePrice($client, 'GR123456789');
        $this->apiCalculatePrice($client, 'FRPR123456789', 'fixed');
        $this->apiCalculatePrice($client, 'FRPR123456789', 'percent');
        $this->apiCalculatePrice($client, 'FRPR123456789');

        $this->apiPurchase($client, 'DE123456789', 'fixed');
        $this->apiPurchase($client, 'DE123456789', 'percent');
        $this->apiPurchase($client, 'DE123456789');
        $this->apiPurchase($client, 'IT12345678900', 'fixed');
        $this->apiPurchase($client, 'IT12345678900', 'percent');
        $this->apiPurchase($client, 'IT12345678900');
        $this->apiPurchase($client, 'GR123456789', 'fixed');
        $this->apiPurchase($client, 'GR123456789', 'percent');
        $this->apiPurchase($client, 'GR123456789');
        $this->apiPurchase($client, 'FRPR123456789', 'fixed');
        $this->apiPurchase($client, 'FRPR123456789', 'percent');
        $this->apiPurchase($client, 'FRPR123456789');


    }
}
