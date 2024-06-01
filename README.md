# Staymenity API

## Requirements

* PHP >= 7.4
* MySQL >= 8.0
* Xdebug >= 2.9 *(for debug)*

## Setup (ENG, current)

1. Build and run Docker containers with _docker-compose_
    Go to *laradock* folder:
    ```shell
    cd laradock
    ```

    Copy configurations (e.g. laravel-horizon.conf.example and other):
    ```shell
    make cp
    ```

    Copy *.env.local* into *.env* file (dev and production env files are for corresponding environments):
    ```shell
    cp .env.local .env
    ```
    
    Build containers (first time only): 
    ```shell
    docker-compose build
    ```
    
    Wait until containers are built.
    
    Run containers (*-d* stands for *"run as daemon"*):
    ```shell
    docker-compose up -d
    ```
    
    Get in *workspace* container:
    ```shell
    docker-compose exec --user=laradock workspace bash
    ```
2. Packages setup and changing Laravel settings
   Inside *workspace* container run the command:
    ```shell
    composer install
    ``` 
   
   Copy .env.example into .env (dev and production are for corresponding environments):
    ```shell
    cp .env.example .env
    ```

   For `documentation` must be .env.documentation file like .env.documentation.example with:
    ```dotenv
    APP_ENV=documentation
    DB_DATABASE=ANOTHER_DATABASE
    API_AUTH_SANCTUM_ENABLED=false
    ```
   Or use command:
    ```shell
    cp .env.documentation.example .env.documentation
    ```

   For `testing` must be .env.testing file like .env.testing.example with:
    ```dotenv
    APP_ENV=testing
    DB_DATABASE=ANOTHER_DATABASE
    API_AUTH_SANCTUM_ENABLED=true
    ```
   Or use command:
    ```shell
    cp .env.testing.example .env.testing
    ```

   Generating Laravel app key (if it's **not** inside .env file):
    ```shell
    php artisan key:generate
    ```

   To make `storage` folder accessible:
    ```shell
    php artisan storage:link
    ```

   Run migrations (still inside *workspace* container) and seeds:
    ```shell
    // local & dev envs only
    php artisan migrate --seed
   
    // on production env
    php artisan migrate
    php artisan db:seed
    ```

3. Laravel .env file changes
    
    Check following constants, read the .env.example for info:
    ```dotenv
    APP_URL
    WEB_URL
    API_URL
    IMAGE_URL
    CMF_URL
    All Database-related constants
    REDIS_HOST
    API_DOMAIN
    ```

#### Useful commands

* To stop containers run the following command in *laradock* folder:
    ```shell
    docker-compose stop
    ```

* To re-build containers (run in *laradock* folder):
    ```shell
    docker-compose up -d --build
    // or
    docker-compose build --no-cache
    ```

* Before any new seeder run:
    ```shell
    composer dump-autoload
    ```

* Build api documentation (existing documentation table):
    ```shell
    make scribe
    ```
  Documentation will be on `{API_URL}/docs/`
  
  
* Build api documentation (empty documentation table or if table was changed):
    ```shell
    make scribe-fresh
    ```
  
* Optimize configurations and routes (if API don't response 200 or seen error like a 'The version given was unknown or has no registered routes.'):
    ```shell
    make optimize
    ```
  
* Analyse code by PHP Code Sniffer (rules on *ruleset.xml*)
    ```shell
    make phpcs
    ```
  
* Analyse code by PHPStan (rules on *phpstan.neon*)
    ```shell
    make analyse
    ```
  
* Build styles and scripts for admin
    ```shell
    npm install
    npm run cmf-watch
    npm run cmf-dev
    npm run cmf-prod
    ```
## Setup & Development (RUS, Obsolete) 

### Локальный запуск

* Копировать конфиг

```bash
cp .env.example .env
```

**Исполнить следующие шаги если используется docker**

* Установить значения следующих параметров:

    1. `COMPOSE_PROJECT_NAME` *можно использовать значение по умолчанию*
    2. `DOCKER_PHP_USER` *имя пользователя в системе*
    3. `DOCKER_PHP_USER_UID` *uid пользователя в системе (используется для проброса прав на директории)*

* Собрать контейнеры

```bash
docker-compose build
```

* Запуск

```bash
docker-compose up -d
```

**Исполнить следующие шаги если используется [Laravel Homestead](https://laravel.com/docs/7.x/homestead)**

* Установить зависимости:

```bash
composer install
```

* Сгенерировать ключ:

```bash
php artisan key:generate
```

* Создать ссылку на директорию `storage` из `public`:

```bash
php artisan storage:link
```

* Запустить миграции и загрузить данные по умолчанию:

```bash
php artisan migrate --seed
```

### API документация

Сборка документации

```bash
make scribe
```

Документация доступна по адресу `/docs`.

* Будут доступны следующие файлы:

    1. Postman Collection в директории `public/docs/collection.json`
    2. OpenAPI (Swagger) Spec в директории `public/docs/openapi.yaml`

## Testing

Для написания и запусков тестов используется Phpunit.

### Запуск с покрытием

Если php работает в docker, предварительно нужно в него зайти

```bash
docker-compose exec php bash
```

Запуск тестов

```bash
./vendor/bin/phpunit
```
