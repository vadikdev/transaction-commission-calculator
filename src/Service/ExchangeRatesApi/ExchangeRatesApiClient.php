<?php

namespace App\Service\ExchangeRatesApi;

use App\Service\ExchangeRatesApi\Transfer\ResponseTransfer;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExchangeRatesApiClient
{
    public function __construct(protected HttpClientInterface $client, protected string $accessKey)
    {
    }

    /**
     * @throws HttpExceptionInterface
     */
    public function latest(array $currencyCodes, string $baseCurrencyCode): ResponseTransfer
    {
        $response = $this->client->request('GET', 'latest', [
            'query' => [
                'symbols' => implode(',', $currencyCodes),
                'base' => $baseCurrencyCode,
                'access_key' => $this->accessKey,
            ],
        ]);

        $content = $response->toArray();

        return (new ResponseTransfer())
            ->fromArray($content);
    }
}
