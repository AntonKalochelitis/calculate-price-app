current_directory := $(shell pwd)

include $(current_directory)/.env
export

DOCKER_COMPOSE = sudo docker-compose -f $(current_directory)/.docker/docker-compose.yml --env-file=$(current_directory)/.env

up:
	${DOCKER_COMPOSE} up -d --build --remove-orphans

build:
	${DOCKER_COMPOSE} build

start:
	${DOCKER_COMPOSE} start

stop:
	${DOCKER_COMPOSE} stop

rm:
	${DOCKER_COMPOSE} rm

restart: stop start

composer_update:
	sudo docker exec -t ${PHPFPM_CONTAINER_NAME} bash -c 'composer update'

composer_install:
	sudo docker exec -t ${PHPFPM_CONTAINER_NAME} bash -c 'composer install'

test_install:
	sudo docker exec -t ${PHPFPM_CONTAINER_NAME} bash -c './bin/console doctrine:database:create --env=test'
	sudo docker exec -t ${PHPFPM_CONTAINER_NAME} bash -c './bin/console doctrine:migrations:migrate --env=test  --no-interaction'

make_migration:
	sudo docker exec -t ${PHPFPM_CONTAINER_NAME} bash -c './bin/console make:migration'

migration:
	sudo docker exec -t ${PHPFPM_CONTAINER_NAME} bash -c './bin/console doctrine:migrations:migrate --no-interaction'

migration_down:
	sudo docker exec -t ${PHPFPM_CONTAINER_NAME} bash -c './bin/console doctrine:migrations:migrate prev --no-interaction'

test:
	sudo docker exec -t ${PHPFPM_CONTAINER_NAME} bash -c './vendor/bin/phpunit'