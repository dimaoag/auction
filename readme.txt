HOST=user@194.22.928.28 PORT=22 REGISTRY=registry.deworker.pro IMAGE_TAG=master-1 BUILD_NUMBER=1 make deploy

REGISTRY=docker.io/1926odad/auction IMAGE_TAG=master-1 make build
REGISTRY=docker.io/1926odad/auction IMAGE_TAG=master-1 make push

docker-compose exec api-php-fpm php -v
docker-compose run --rm api-php-cli php -v
docker-compose run --rm api-php-cli composer
docker-compose run --rm api-php-cli composer require slim/slim slim/psr7
docker-compose run --rm api-php-cli php bin/app.php hello
docker-compose run --rm api-php-cli composer app // the same command
docker-compose run --rm api-php-cli ip route