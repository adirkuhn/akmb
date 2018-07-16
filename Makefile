build:
	docker-compose build app

up:
	docker-compose up -d app

clean:
	docker ps -af "label=akmb.service" --format "{{.Names}}" | xargs --no-run-if-empty docker rm -f


psr2:
	docker-compose exec -T app php ./vendor/bin/phpcs --standard=PSR2 --ignore=vendor .