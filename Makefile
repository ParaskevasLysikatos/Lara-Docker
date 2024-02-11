setup:
	@make build
	@make up 
	@make composer-update
build:
	docker-compose build --no-cache --force-rm
stop:
	docker-compose stop
up:
	docker-compose up -d
composer-update:
	docker exec laravel-docker bash -c "composer update"
data:
	docker exec laravel-docker bash -c "php artisan migrate:refresh --seed"
cache:
	docker exec laravel-docker bash -c "php artisan cache:clear"
test-f:
	docker exec laravel-docker bash -c "php artisan test --testsuite=Feature"
test-u:
	docker exec laravel-docker bash -c "php artisan test --testsuite=Unit"
test:
	docker exec laravel-docker bash -c "php artisan test"