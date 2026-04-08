## WeightRoom.uk

Built on laravel framework.

## Docker

This repo now includes a basic Docker setup for the legacy Laravel 5.5 app.

1. Copy `.env.example` to `.env`
2. Generate an application key
3. Start the containers
4. Run migrations if you need a fresh local database

```bash
cp .env.example .env
docker compose up --build -d
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
```

The app is exposed on `http://localhost:8080`.

### License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
