<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use SimpleXMLElement;

class PrestashopService
{
    public function request(string $method, string $resource, array $query = [], ?string $xmlBody = null): SimpleXMLElement
    {
        $baseUrl = rtrim((string) config('prestashop.base_url'), '/');
        $apiKey  = (string) config('prestashop.api_key');
        $timeout = (int) config('prestashop.timeout', 15);

        $url = $baseUrl . '/api/' . ltrim($resource, '/');

        $response = Http::withBasicAuth($apiKey, '')
            ->timeout($timeout)
            ->accept('application/xml')
            ->send($method, $url, [
                'query'   => $query,
                'headers' => ['Content-Type' => 'application/xml'],
                'body'    => $xmlBody,
            ]);

        $response->throw();

        return new SimpleXMLElement($response->body());
    }
}