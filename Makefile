build:
	docker-compose build app

up:
	docker-compose up -d app
	make composer

clean:
	docker-compose stop
	docker-compose rm -f

composer:
	docker-compose exec -T app php ./bin/composer.phar install

logs:
	docker-compose exec app $(tail -f /var/log/*/*)

psr2:
	docker-compose exec -T app php ./vendor/bin/phpcs --standard=PSR2 --ignore=vendor .

unit-tests:
	docker-compose exec -T app php ./vendor/bin/phpunit --stderr --bootstrap ./vendor/autoload.php --colors=always tests