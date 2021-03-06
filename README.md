# JWT Auth

A JWT authentication package to use in Laravel applications.

## Installation

Use [composer](https://getcomposer.org) to install the JWT Auth package.

```sh
composer require carter-parker/jwt-auth
```

## Usage

Run the following command:

```sh
php artisan vendor:publish --provider="CarterParker\JWTAuth\Providers\JWTAuthServiceProvider"
```

Running this command will give you the following configuration files:

- `auth.php`
- `jwt.php`
- `jwt-auth.php`

Inside of the `jwt-auth.php` you will find a configuration option where you can change the prefix for the route. By default this is set to `/auth`.

## Routes

Upon installing your new routes will be registered.

Making a POST request to `{prefix}/login` with valid credentials will give you the following response.

```json
{
  "access_token": "eyJ0eX...",
  "token_type": "Bearer",
  "expires_in": 3600
}
```

You will need to store the `access_token` property in local storage. This is used to authenticate the user in subsequent requests.

## Protecting API Routes

To protect your routes in the api you should use the following middleware:

```js
['auth', 'refresh']
```

- Auth ensures that the user has a valid JWT token and is authenticated.
- Refresh blacklists the token used to authorize & returns a new valid token in the response headers.

## User Model

You will need to add the following interface to your user model `Tymon\JWTAuth\Contracts\JWTSubject` and add the following abstract methods towards the bottom of the model.

```php
/**
 * Get the identifier that will be stored in the subject claim of the JWT.
 *
 * @return mixed
 */
public function getJWTIdentifier()
{
    return $this->getKey();
}

/**
 * Return a key value array, containing any custom claims to be added to the JWT.
 *
 * @return array
 */
public function getJWTCustomClaims()
{
    return [];
}
```

## Making Requests

When making requests to the API you need to bear in mind that the token is short lived and will only be valid for 1 request. 

The response authorization header will need to be overwrite the current token stored and used for the next request.

## Password Resets

In order to set up password resets there are a few steps which you need to take.

- Ensure that you have a password_resets table (this comes default with Laravel)
- Add the following methods to your user model

```php
/**
 * Mutate the password to be hashed on entry into the database.
 */
public function setPasswordAttribute(string $value): void
{
    $this->attributes['password'] = app('hash')->make($value);
}

/**
 * Get the email address of the user for the password reset.
 */
public function getEmailForPasswordReset(): string
{
    return $this->email;
}

/**
 * Send the password reset notification.
 */
public function sendPasswordResetNotification($token)
{
    $this->notify(new ResetPasswordNotification($token));
}
```

The package come pre-shipped with a basic reset password notification which you can use by using `CarterParker\JWTAuth\Notifications\ResetPasswordNotification`

## Configuration

Inside of the configuration you are able to change the following properties:

- `prefix` - This is the prefix that is used in front of ALL authentication requests that go through the package.
- `current_user.route` - This allows you to specify a seperate endpoint for getting the currently logged in user.
- `current_user.attributes` - These are the attributes that you want to come back from the request to get the current user.

## Methods

| Uri              | Method | Required                                               |
|------------------|--------|--------------------------------------------------------|
| /{prefix}/login           | POST   | email, password                               |
| /{prefix}/forgot-password | POST   | email                                         |
| /{prefix}/verify-token    | POST   | email, token                                  |
| /{prefix}/reset-password  | POST   | email, token, password, password_confirmation |
| /{currentUserRoute}       | GET    | -                                             |

## License
[MIT](https://choosealicense.com/licenses/mit/)