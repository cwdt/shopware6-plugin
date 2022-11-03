<?php declare(strict_types=1);

namespace Picqer\Shopware6Plugin\Client;

final class CurlClient implements Client
{
    const USER_AGENT = 'Picqer Shopware 6 Plugin (version 0.1.0)';

    public function pushOrder(string $subdomain, string $connectionKey, string $id): void
    {
        $this->post(
            sprintf('https://%s.picqer.com/webshops/shopware6/orderPush/%s', $subdomain, $connectionKey),
            ['id' => $id]
        );
    }

    private function post(string $url, array $body): void
    {
        $session = curl_init();

        curl_setopt($session, CURLOPT_USERAGENT, self::USER_AGENT);

        curl_setopt($session, CURLOPT_URL, $url);
        curl_setopt($session, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);

        curl_setopt($session, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($session, CURLOPT_POSTFIELDS, json_encode($body));

        curl_exec($session);
        curl_close($session);
    }
}