.PHONY: build
build: ## Build images for symfony app and sms mock api
	docker compose build --no-cache && docker compose -f docker-compose.smsapi.yml build --no-cache

.PHONY: start
start: ## Start containers for symfony app and sms mock api
	docker compose up --pull -d --wait && docker compose -f docker-compose.smsapi.yml up --pull -d --wait

.PHONY: stop
stop: ## Stop containers for symfony app and sms mock api
	docker compose down --remove-orphans && docker compose -f docker-compose.smsapi.yml down --remove-orphans

.PHONY: bash-app
bash-app: ## Bash into symfony app container
	docker exec -it symfony-docker-php-1 bash

.PHONY: bash-sms
bash-sms: ## Bash into sms container
	docker exec -it symfony-docker-smsapi-1 bash


local-setup:
	make build
	make migrate
	make queue

queue:
	docker exec symfony-docker-php-1 bin/console messenger:consume -vv

migrate:
	docker exec symfony-docker-php-1 bin/console doctrine:migrations:migrate --allow-no-migration --no-interaction

php-stan:
	docker exec symfony-docker-php-1 vendor/bin/phpstan analyse --memory-limit=-1

ecs:
	docker exec symfony-docker-php-1 vendor/bin/ecs --fix
