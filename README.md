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
   http://127.0.0.1:9580/api/doc

## Working URLs
```shell
    GET    http://127.0.0.1:9580/api/doc - Documentation
    POST   http://127.0.0.1:9580/coupon/generate - Generate 200 coupons
    POST   http://127.0.0.1:9580/api/calculate/price - Calculate product price
    POST   http://127.0.0.1:9580/api/purchase - Payment 
```
