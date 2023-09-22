INPUT_FILE_PATH = /eastern-enterprise/data/addresses/input/addresses.txt
OUTPUT_FILE_PATH = /eastern-enterprise/data/addresses/output/addresses.csv

up:
	docker compose up -d

down:
	docker compose down

composer-install:
	docker exec app composer install

run-addresses-distance-processor:
	docker exec app php artisan app:addresses-distances-processor ${INPUT_FILE_PATH} ${OUTPUT_FILE_PATH}

test:
	docker exec app php artisan test