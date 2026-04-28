# Salon Vision Backend

Laravel backend API for the Salon Vision project.

## Stack

- PHP 8.2+
- Composer
- Laravel 11
- MySQL
- Node.js + npm (for Vite assets)

## 1) Install dependencies

```bash
composer install
npm install
```

## 2) Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

Then update `.env` database values (`DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) for your local MySQL.

## 3) Prepare database

```bash
php artisan migrate
php artisan db:seed
```

If your setup relies on Passport password grant, create/update a password client:

```bash
php artisan passport:client --password --provider=users
```

## 4) Run the backend

```bash
php artisan serve
```

Default local API URL:

- `http://127.0.0.1:8000`

## Optional: run Vite in development

```bash
npm run dev
```

## Useful commands

```bash
php artisan route:list
php artisan optimize:clear
```
