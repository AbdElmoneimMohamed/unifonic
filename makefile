# Executables (local)
DOCKER_COMP = docker-compose

# Docker containers
PHP_CONT = $(DOCKER_COMP) exec php

# Executables
PHP      = $(PHP_CONT) php
COMPOSER = $(PHP_CONT) composer
SYMFONY  = $(PHP_CONT) bin/console


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
	@$(PHP_CONT) sh

.PHONY: bash-sms
bash-sms: ## Bash into sms container
	docker exec -it symfony-docker-smsapi-1 bash


local-setup:
	make build
	make start
	make migrate
	make queue

queue:
	@$(SYMFONY) messenger:consume -vv

migrate:
	@$(SYMFONY) doctrine:migrations:migrate --allow-no-migration --no-interaction

php-stan:
	@$(PHP_CONT) vendor/bin/phpstan analyse --memory-limit=-1

ecs:
	@$(PHP_CONT) vendor/bin/ecs --fix
