# TrustFully API PHP Client

A simple Object Oriented wrapper for TrustFully API, written with PHP5.

See TrustFully API's documentation.

## Features

* Follows PSR-0 conventions and coding standard: autoload friendly
* API entry points implementation state :
 * Community
 * Sponsorship
 * Membership
 * User

## Requirements

* PHP >= 5.4
* The PHP [cURL](http://php.net/manual/en/book.curl.php) extension
* The PHP [JSON](http://php.net/manual/en/book.json.php) extension
* [PHPUnit](https://phpunit.de/) >= 4.0 (optional) to run the test suite

## Install

```bash
$ composer require trustfully/trustfully-api-client-php
```

```php
<?php

require_once 'vendor/autoload.php';

try {
    $client = new TrustFully\Client('https://api.trustfully.com/v1/', 'CLIENT_API_KEY');

    $me = json_decode($client->login->getToken('guillaume@gensdeconfiance.fr', 'azerty'));
    $client->setApiToken($me->token);

    $client->setApiToken($sessionToken);
    // ...
    $communities = $client->community->all();
    // ...
} catch (\Exception $e) {
    die($e->getMessage());
}
```
