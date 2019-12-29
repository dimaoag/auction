HOST=user@194.22.928.28 PORT=22 REGISTRY=registry.deworker.pro IMAGE_TAG=master-1 BUILD_NUMBER=1 make deploy

docker-compose exec api-php-fpm php -v
docker-compose run --rm api-php-cli php -v
docker-compose run --rm api-php-cli composer