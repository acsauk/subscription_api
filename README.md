# Subscriptions API

## Introduction
A small API that facilitates subscribing and unsubscribing phone numbers to subscription products as well as searching for existing subscriptions.

## Overview
Allows submission of `msisdn` (phone number) and `product_id` via query string params (GET) or JSON/x-www-form-urlencoded (POST).

To subscribe to a product an msisdn and product_id is required. Likewise, to unsubscribe the two values are required.

When searching for a subscription the mobile phone number can be provided in a local format, starting with 07, or international format, starting with +447. Additionally if a subscription has been created using an Alias for a phone number, starting with an A character, this can also be provided in place of a phone number.

## Installation/Config

- Clone this repo locally
- Pre-requirements:
  - PHP (install with `brew install php70`)
  - Composer (install with `brew install composer`)
  - MySql (install with `brew install mysql` or with an installation package direct from [MySql](https://dev.mysql.com/downloads/installer/))
- Once pre-requirements are installed run `composer install` to install project dependencies
- Update the project .env file with MySql details:
  - DB_CONNECTION=mysql
  - DB_HOST=127.0.0.1
  - DB_PORT=3306
  - DB_DATABASE=subscription_api
  - DB_USERNAME=<YOUR USERNAME HERE>
  - DB_PASSWORD=<YOUR PASSWORD HERE>
- Run `php artisan migrate`

## Tests

To run the test suite use `composer test`

## Endpoint documentation

Detailed endpoint documentation is available via [Postman](https://documenter.getpostman.com/view/2462810/subscriptions-api/RVnWhJtN#intro) along with example payloads and responses.
