<?php

namespace App\Service\BinList;

use App\Service\BinList\Transfer\LookupResponseTransfer;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BinListClient
{
    public function __construct(protected HttpClientInterface $client)
    {
    }

    /**
     * @throws HttpExceptionInterface
     */
    public function lookup(string $bin): LookupResponseTransfer
    {
        $response = $this->client->request('GET', $bin);
        $content = $response->toArray();

        return (new LookupResponseTransfer())
            ->fromArray($content);
    }
}
