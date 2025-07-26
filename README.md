# Rosalana Accounts

This package is a part of the Rosalana eco-system. It provides a way to manage accounts and sync users in the eco-system. It uses the Basecamp API to get users and their accounts.

> **Note:** This package is a extension of the [Rosalana Core](https://packagist.org/packages/rosalana/core) package.

## Table of Contents

- [Installation](#installation)
- [Configuration](#configuration)
- [Features](#features)
  - [Accounts](#accounts)
  - [Basecamp Bindings](#basecamp-bindings)
  - [Stubs](#stubs)
- [May Show in the Future](#may-show-in-the-future)
- [License](#license)

## Installation

To install the `rosalana/accounts` package, you must first have the `rosalana/core` package installed. If you haven't installed it yet, please refer to the `rosalana/core` documentation.

After installing the `rosalana/core` package, use the `rosalana:add` command from the Rosalana CLI and select `rosalana/accounts` from the list:

```bash
php artisan rosalana:add
```

After installing the package, you should publish its assets using the following command:

```bash
php artisan rosalana:publish
```

You can specify which files to publish. Publishing **the configuration files is required** to set up the package properly. Other files are optional and can be published as needed. However, it is recommended to publish all files to take full advantage of the package features.

> Do not forget to add the `rosalana_account_id` identifier to `$fillable` array of your `Authenticable` model. This is required for the package to work properly.

## Configuration

After publishing the package, you will find a `rosalana.php` configuration file in the `config` directory of your Laravel application. You can customize these options according to your needs.

This file will grow over time as you add more Rosalana packages to your application. Each package contributes its own configuration section. The `rosalana.php` file serves as the central configuration hub for all Rosalana packages.

`rosalana/accounts` package provides configuration options for:

- `accounts`: This section contains bindings between the Basecamp Account and the local Authenticable model. It allows you to specify model or Basecamp Account IDs for each account. This is useful for syncing users and their accounts in the Rosalana eco-system.

## Features

### Accounts

The `rosalana/accounts` package integrates deeply with Laravel’s native authentication system, while seamlessly connecting your application to the `Rosalana Basecamp` server.

This means you can continue using Laravel’s standard auth() functions, guards, and session handling — but behind the scenes, all authentication requests (login, register, logout, refresh) are securely forwarded to `Basecamp` and handled `automatically`.

> You don’t need to manually handle API tokens — the session system handles it automatically behind the scenes.

#### Automatic User Syncing

Whenever a user logs in or registers through Basecamp, their data is automatically synchronized with your local application using the configured model and identifier:

- If the user exists locally → it’s updated.
- If not → a new local user is created.

This enables cross-app authentication across the Rosalana ecosystem — logging into one app makes the user accessible in others, without needing to register again.

#### Logging In

You can authenticate the user using a single command:

```php
use Rosalana\Accounts\Facades\Accounts;

$user = Accounts::login([
    'email' => 'john@example.com',
    'password' => 'secret',
]);
```

- This will contact Basecamp, validate creadentials, fetch user data and token, and create a valid Laravel session.
- The authenticated user is returned as an instance of you configured `Authenticable` model.

#### Registering a New User

```php
$user = Accounts::register([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => 'secret',
]);
```

This creates a new user in Basecamp, synchronizes it locally, and logs the user in.

#### Logging Out

```php
Accounts::logout();
```

This logs the user out from both your application and Basecamp, and fully clears their session and token.

#### Refreshing the Session Token

```php
Accounts::refresh();
```

- This will attempt to get a new token from Basecamp using the currently stored one.
- If the token is still valid → it will be replaced and the session continues.
- If the token is invalid or expired → the user is logged out automatically.

#### Accessing the Current User

You can keep using Laravel's standard way:

```php
auth()->user();
```

This works seamlessly, as `rosalana/accounts` uses Laravel's built-in authentication engine under the hood - no custom guards, no suprises.

#### Advanced Access

In some cases, you may want to interact directly with the session or token management:

```php
// Get the currently stored Basecamp token
$token = Accounts::token()->get();

// Manually refresh the session token
Accounts::session()->refresh($newToken);

// Fully clear session and token
Accounts::session()->terminate();
```

This gives you full control if you need to customize how authentication is handled in special cases (e.g. background jobs, API auth, etc).

### Basecamp Bindings

The `rosalana/accounts` package registers a `Basecamp Bindings` under the key `users` and `auth`. This means that you can use the `Basecamp` facade to access the Basecamp API and get users.

```php
use Rosalana\Accounts\Facades\Basecamp;

Basecamp::users()->find($id);
Basecamp::auth()->current();
Basecamp::auth()->login($email, $password);
```



> Bindings provides pre-configured methods which internally handle the Basecamp API requests, including authentication and pipeline integration. You no longer need to manually specify endpoints, tokens or headers.

Beaware that bindings are just a route definitions. No handling is done after that. For further usage needs to be extended somewhere.

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

## May Show in the Future

- **Password Reset**: A feature to reset the password for a user.
- **Email Verification**: A feature to verify the email address of a user.
- **OAuth2**: A feature to enable OAuth2 authentication for a user.
- **Two-Factor Authentication**: A feature to enable two-factor authentication for a user.

Stay tuned — we're actively shaping the foundation of the Rosalana ecosystem.

## License

Rosalana Accounts is open-source under the [MIT license](/LICENCE), allowing you to freely use, modify, and distribute it with minimal restrictions.

You may not be able to use our systems but you can use our code to build your own.

For details on how to contribute or how the Rosalana ecosystem is maintained, please refer to each repository’s individual guidelines.

**Questions or feedback?**

Feel free to open an issue or contribute with a pull request. Happy coding with Rosalana!
