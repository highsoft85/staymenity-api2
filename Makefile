#!/usr/bin/env make

.PHONY : help swagger

.DEFAULT_GOAL := help
SRC ?= ./app
TESTS ?= ./tests
DBS ?= ./database
ROUTES ?= ./routes
OS := $(shell uname -s)
COMPOSER := $(shell which composer)
PHP := php
NPM := npm
PHPUNIT := ./vendor/bin/phpunit

help: ## Показать эту подсказку
	@echo "Сборка. Staymenity"

swagger: ## Генерация сваггер файла в storage/api-docs/api-docs.json
	${PHP} ./artisan l5-swagger:generate

scribe: ## Генерация scribe документации
	rm -r ./public/docs || true
	rm -r ./resources/docs || true
	${PHP} ./artisan config:clear
	${PHP} ./artisan route:clear
	${PHP} ./artisan scribe:generate --env documentation --verbose

scribe-fresh: ## Генерация scribe документации
	rm -r ./public/docs || true
	rm -r ./resources/docs || true
	${PHP} ./artisan migrate:fresh --env=documentation
	${PHP} ./artisan db:seed --class=DocumentationDatabaseSeeder --env=documentation
	${PHP} ./artisan config:clear
	${PHP} ./artisan route:clear
	${PHP} ./artisan scribe:generate --env documentation --verbose

sentry-test: ## Тест подключения к sentry
	${PHP} ./artisan sentry:test

test-without-stripe: ## Тестирование
	${PHP} ${PHPUNIT} --stop-on-failure --configuration=./phpunit.xml --testsuite=Unit,Feature --exclude-group=stripe

test-no-deploy: ## Тестирование
	${PHP} ${PHPUNIT} --stop-on-failure --configuration=./phpunit.xml --testsuite=Unit --exclude-group=no_deploy

test-unit: ## Тестирование
	${PHP} ${PHPUNIT} --stop-on-failure --configuration=./phpunit.xml --testsuite=Unit

demo-state: ## Демо
	${PHP} ./artisan migrate:fresh --seed
	${PHP} ./artisan cases:los-angeles-many
	${PHP} ./artisan cases:los-angeles
	${PHP} ./artisan cases:california-pool
	${PHP} ./artisan cases:california-one-many-reservations
	${PHP} ./artisan cases:barbeque-new-york
	${PHP} ./artisan listing:update-rating
	${PHP} ./artisan reservation:check-passed

test-fresh: ## Тестирование
	${PHP} ./artisan migrate:fresh --env=testing
	${PHP} ./artisan db:seed --class=RolesTableSeeder --env=testing
	${PHP} ./artisan db:seed --class=TypesTableSeeder --env=testing

analyse: ## Проверка psr проекта
	./vendor/bin/phpstan analyse

optimize: ## Очистка роутов
	${PHP} ./artisan optimize
	${PHP} ./artisan api:cache

phpcs: ## Проверка psr проекта
	./vendor/bin/phpcs -n -s -p --standard=ruleset.xml --report-full --colors ./app ./database

phpcbf: ## Проверка psr проекта
	./vendor/bin/phpcbf --standard=ruleset.xml --extensions=php --tab-width=4 -sp ./app/Services/Verification

expand: ## Первое разворачивание проекта
	cp .env.example .env
	${PHP} ./artisan key:generate
	${PHP} ${COMPOSER} install
	${PHP} ./artisan storage:link
	${PHP} ./artisan migrate
	${PHP} ./artisan db:seed

build-npm-backend: ## Собрать скрипты для бэка
	${NPM} install
	${NPM} run cmf-prod

deploy: ## Деплой проекта, если нет автоматического
	make deploy-frontend
	make deploy-backend

deploy-backend: ## Деплой проекта, если нет автоматического
	${PHP} ${COMPOSER} install
	${PHP} ./artisan migrate --force
	make optimize
	${PHP} ./artisan version:git

deploy-frontend: ## Деплой проекта, если нет автоматического
	make build-npm-backend

documentation: ## Документация роутов по отдельному .env
	${PHP} ./artisan documentation:check --testing
	${PHP} ./artisan migrate:fresh --env=documentation
	${PHP} ./artisan db:seed --env=documentation --class=DocumentationDatabaseSeeder
	make scribe

storage-clear: ## Чистка стороджа
	rm -r storage/app/public/images/listing || true
	rm -r storage/app/public/images/user || true
	${PHP} ${COMPOSER} dump-autoload
	${PHP} ./artisan migrate:fresh --seed
---------------: ## ---------------
