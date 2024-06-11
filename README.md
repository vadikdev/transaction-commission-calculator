# Transaction Commission Calculator App

## Requirements

1. `"php": ">=8.2",`
2. [composer](https://getcomposer.org/)

## Set-up

Run `composer install`

Create `.env.local`  file and put `APP_EXCHANGE_RATES_API_ACCESS_KEY` parameter from your https://exchangeratesapi.io/ account.

If your https://exchangeratesapi.io/ doesn't support `ssl` then put `APP_EXCHANGE_RATES_API_BASE_URI` to your `.env.local` file as well (e.g. http://...)

## Usage
Run `./bin/console app:commission:calculate /absolute/path/to/your/transactions/file.txt`

## Testing
Run `./bin/phpunit`
