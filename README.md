## Laravel Weevo Package by [Akika Digital](https://akika.digital)

The Laravel Weevo package allows you to transfer money through the NCBA Open Banking APIs. The package supports Laravel version 5 and above.

## Installation

You can install the package via composer:

```bash
composer require akika/laravel-weevo
```

After installing the package, publish the configuration file using the following command:

```bash
php artisan weevo:install
```

## ENV Variables

You can add the following variables to your env file. Make sure to add the requested information.

```bash
WEEVO_ENV=
WEEVO_DEBUG=
WEEVO_SANDBOX_USERNAME=
WEEVO_SANDBOX_API_KEY=
WEEVO_SANDBOX_API_SECRET=
WEEVO_SANDBOX_URL=
WEEVO_PRODUCTION_USERNAME=
WEEVO_PRODUCTION_API_KEY=
WEEVO_PRODUCTION_API_SECRET=
WEEVO_PRODUCTION_URL=
```
