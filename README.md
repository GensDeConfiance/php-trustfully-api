# TrustFully API PHP Client

A simple Object Oriented wrapper for TrustFully API, written with PHP5.

See TrustFully API's documentation.

## Features

* Follows PSR-0 conventions and coding standard: autoload friendly
* API entry points implementation state :
 * Community
 * Contact
 * Membership
 * Sponsorship
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

## Usage

```php
<?php

require_once 'vendor/autoload.php';

try {
    $client = new TrustFully\Client('https://api.trustfully.com/v1/', 'CLIENT_API_KEY');

    $me = $client->user->login('guillaume@gensdeconfiance.fr', 'azerty');

    // ...
    $communities = $client->community->all();
    // ...
} catch (\Exception $e) {
    die($e->getMessage());
}
```

Alternatively, you can specify a host to bypass DNS resolution when instanciating the client:

```php
<?php

    $client = new TrustFully\Client('https://10.17.10.17/v1/', 'CLIENT_API_KEY', 'api.trustfully.com');

```
