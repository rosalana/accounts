# Rosalana Accounts

This package is a part of the Rosalana eco-system. It provides a way to manage accounts and sync users in the eco-system. It uses the Basecamp API to get users and their accounts.

> **Note:** This package is a extension of the [Rosalana Core](https://packagist.org/packages/rosalana/core) package.

## Table of Contents
- [Features](#features)
    - [Users Service](#users-service)
    - [RosalanaAuth Facade](#rosalanaauth-facade)
    - [auth.rosalana Middleware](#authrosalana-middleware)
    - [Stubs](#stubs)
- [Installation](#installation)
- [Configuration](#configuration)
- [License](#license)

<!-- - [May Show in the Future](#may-show-in-the-future) -->

## Features

### Users Service

The `UsersService` extends the `Basecamp Facade` and provides a way to get users from the Basecamp API.

```php
Basecamp::users()->login($credentials);
```

### RosalanaAuth Facade

The `RosalanaAuth::class` is a facade that provides a way to authenticate users in the Rosalana eco-system. It handles the authentication and syncs the user with the accounts.

In the background, it users the `UsersService` to get access to the Basecamp API.

```php
RosalanaAuth::login($credentials);
RosalanaAuth::logout();
```

It also create a local session for the user, managing cookies for SPA.

### auth.rosalana Middleware

The `auth.rosalana` middleware is a middleware that checks if the user is authenticated via the access token. Checks are made offline, so it doesn't make any requests to the Basecamp API.

If the token is no longer valid it will try to refresh it via the `RosalanaAuth` facade. If the refresh fails, the user will be logged out.

### Stubs

To make everything easier, the package provides predefined files for user authentication. Such as controllers for login, logout, and registration, Requests for validation, and routes.

```bash
Http
├── Controllers
│   └── Auth
│       ├── AuthenticatedSessionController.php
│       └── RegisteredUserController.php
└── Requests
    └── Auth
        ├── LoginRequest.php
        └── RegisterRequest.php
routes
├── web.php
├── api.php
└── auth.php
```

## Installation

You can install `rosalana/accounts` via Composer by running the following command:

```bash
composer require rosalana/accounts
```

After installing the package, you can publish its assets using the following command:

```bash
php artisan rosalana:accounts:install
```

> **Warning:** This is a one-time operation, don't run it multiple times.

> **Note:** API of commands may change in the future versions.

## Configuration

The configuration file is located at `config/rosalana.php`. You can modify the configuration file to change the behavior of the package.

This file is published after installing `rosalana/core` package.


## License

Rosalana Accounts is open-source under the [MIT license](/LICENCE), allowing you to freely use, modify, and distribute it with minimal restrictions.

You may not be able to use our systems but you can use our code to build your own.

For details on how to contribute or how the Rosalana ecosystem is maintained, please refer to each repository’s individual guidelines.

**Questions or feedback?**

Feel free to open an issue or contribute with a pull request. Happy coding with Rosalana!
