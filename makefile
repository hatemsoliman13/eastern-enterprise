up:
	docker compose up -d

down:
	docker compose down

composer-install:
	docker exec app composer install