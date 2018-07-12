build:
	docker-compose build app

up:
	docker-compose up -d app

clean:
	docker ps -af "label=akmb.service" --format "{{.Names}}" | xargs --no-run-if-empty docker rm -f