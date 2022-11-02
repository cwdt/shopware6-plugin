<?php declare(strict_types=1);

namespace Picqer\Shopware6Plugin\Client;

final class CurlClient implements Client
{
    public function pushOrder(string $subdomain, string $connectionKey, string $id): void
    {
        $this->post(
            sprintf('https://%s.picqer.com/webshops/shopware6/orderPush/%s', $subdomain, $connectionKey),
            ['id' => $id]
        );
    }

    private function post(string $url, array $body): void
    {
        $curlSession = curl_init();

        curl_setopt($curlSession, CURLOPT_URL, $url);
        curl_setopt($curlSession, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);

        curl_setopt($curlSession, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curlSession, CURLOPT_POSTFIELDS, json_encode($body));

        curl_exec($curlSession);
        curl_close($curlSession);
    }
}