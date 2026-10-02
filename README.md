## Requirement & Feature

-   Laravel 8
-   Vue + VueRouter + Vuex
-   Login, register, update profile
-   password reset
-   Authentication with Sanctum
-   Tailwind + Heroicons

## Installation

-   `git clone git@github.com:HijenHEK/laravel-vue-sanctum-spa.git --branch v1.0.0 my-spa`  set verion and app name 
-   `cd my-spa`
-   Edit `.env` and set your database connection details and **your APP_URL** 
-   `php artisan key:generate`
-   `php artisan migrate`
-   `npm install`
-   `npm run dev`

## Notes
- make sure your domain is included in the statefull allowed domains (app/config/sanctum.php) to avoid [Unauthorised domains issue #3](/../../issues/3).

## Usage

#### Development

```bash
npm run watch

```

#### Production

```bash
npm run production
```
