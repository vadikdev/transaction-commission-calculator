# Transaction Commission Calculator App

## Requirements

1. `"php": ">=8.2",`
2. [composer](https://getcomposer.org/)
3. [ExchangeRatesApi](https://exchangeratesapi.io/) access key

## Set-up

Run `composer install`

Create `.env.local`  file and put `APP_EXCHANGE_RATES_API_ACCESS_KEY` parameter from your https://exchangeratesapi.io/ account.

If your https://exchangeratesapi.io/ doesn't support `ssl` then copy`APP_EXCHANGE_RATES_API_BASE_URI` parameter from `.env` file to your `.env.local` file and replace `htts://` with `http://`.

Your parameter should look like this: `APP_EXCHANGE_RATES_API_BASE_URI=http://api.exchangeratesapi.io/v1/`

## Usage
Run `./bin/console app:commission:calculate /absolute/path/to/your/transactions/file.txt`

## Testing
Run `./bin/phpunit`

## Limitations
This app uses [BinList](https://binlist.net/) API for bin lookup and by default this service allows **5 requests per 1 hour**
