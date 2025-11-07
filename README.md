# Restaurant API (Symfony)

## Installation
1. `composer install`
2. Configurer `.env` (DATABASE_URL pour sqlite: `sqlite:///%kernel.project_dir%/var/data_dev.db`)
3. `php bin/console doctrine:database:create`
4. `php bin/console doctrine:migrations:migrate`
5. `symfony server:start` ou `php -S localhost:8000 -t public`

## Endpoints
- POST `/api/reservations`
- GET `/api/reservations`
- POST `/api/orders`
- GET `/api/orders`

## CORS
Autorisé pour `http://localhost:3000` 
