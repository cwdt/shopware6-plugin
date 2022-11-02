<?php

namespace Picqer\Shopware6Plugin\Client;

interface Client
{
    public function pushOrder(string $subdomain, string $connectionKey, string $id): void;
}