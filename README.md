# calculate-price-app

## Installation of the project
1. Rename `.env.dist` в `.env`
   Open file `.env` and set the secret key:
   APP_SECRET=your_secret_here

2. To start the project, run the following command in the root directory:
```shell
$ make up
```

3. To install dependencies, run:
```shell
$ make composer_install
```

4. Set extended permissions for the var folder in the project root:
```shell
$ sudo chmod -R 777 ./var
```

5. To apply migrations, run the following command in the project root:
```shell
make migration
```

6. After successful installation, access the documentation at the following link:
   http://127.0.0.1:9950/api/doc


7. For run tests: Run once
```shell
make test_install
```

8. To run tests:
```shell
make test
```


## Working URLs
```shell
    GET    http://127.0.0.1:9580/api/doc - Documentation
    POST   http://127.0.0.1:9580/coupon/generate - Generate 200 coupons
    POST   http://127.0.0.1:9580/api/calculate/price - Calculate product price
    POST   http://127.0.0.1:9580/api/purchase - Payment 
```

```shell
curl --request POST \
--url http://192.168.57.101:9950/api/calculate/price \
--header 'Content-Type: application/json' \
--data '{
"product": 1,
"taxNumber": "DE123456789"
}'

Response1:
{
	"product_name": "Iphone",
	"product_amount": "100.00 EUR",
	"coupon": "5.00 EUR",
	"product_with_coupon": "95.00 EUR",
	"tax": "19%",
	"costing_tax": "18.05 EUR",
	"costing_amount": "113.05 EUR"
}
```

```shell
curl --request POST \
--url http://127.0.0.1:9950/api/purchase \
--header 'Content-Type: application/json' \
--data '{
"product": 1,
"taxNumber": "IT12345678900",
"couponCode": "CG7HZSZK-HHA2LLXF-VS31IO16",
"paymentProcessor": "paypal"
}'

Response1:
{
"order_id": 1,
"product_name": "Iphone",
"product_amount": "100.00 EUR",
"coupon": "0.00 EUR",
"product_with_coupon": "100.00 EUR",
"tax": "22%",
"costing_tax": "22.00 EUR",
"costing_amount": "122.00 EUR"
}

Response2:
{
"order_id": 2,
"product_name": "Iphone",
"product_amount": "100.00 EUR",
"coupon": "5.00 EUR",
"product_with_coupon": "95.00 EUR",
"tax": "22%",
"costing_tax": "20.90 EUR",
"costing_amount": "115.90 EUR"
}
```