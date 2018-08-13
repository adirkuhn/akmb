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
	docker-compose exec -T app tail -f /var/log/messages

psr2:
	docker-compose exec -T app php ./vendor/bin/phpcs --standard=PSR2 --ignore=vendor .

unit-tests:
	docker-compose exec -T app php ./vendor/bin/phpunit --stderr --bootstrap ./vendor/autoload.php --colors=always tests

analyser:
	docker-compose exec -T app php ./vendor/bin/phpstan analyse -l0 src tests

send-messages:
	docker-compose exec -T app php ./bin/send_message.php
