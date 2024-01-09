# Freelance web-site

## Installation

### Docker

```shell
cd docker/
```

```shell
cp .env.example .env
```

```shell
docker compose up -d
```

### Composer

In container:

```shell
composer install
```

### Database

```shell
cp .env.example .env
```

```shell
cd docker/
docker compose exec php bash
```

```shell
php artisan migrate
# next will fail but create necessary tables
php artisan doctrine:migrations:exec --up 20200929180848
```

```shell
php artisan db:seed
```

### Run

```shell
php artisan key:generate
```

see http://localhost:8000|
