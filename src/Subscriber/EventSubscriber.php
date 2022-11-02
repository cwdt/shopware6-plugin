<?php declare(strict_types=1);

namespace Picqer\Shopware6Plugin\Subscriber;

use Exception;
use Picqer\Shopware6Plugin\Client\Client;
use RuntimeException;
use Shopware\Core\Checkout\Order\OrderEvents;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenEvent;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class EventSubscriber implements EventSubscriberInterface
{
    private $configService;
    private $client;

    public function __construct(SystemConfigService $configService, Client $client)
    {
        $this->client = $client;
        $this->configService = $configService;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OrderEvents::ORDER_WRITTEN_EVENT => 'pushOrder',
        ];
    }

    public function pushOrder(EntityWrittenEvent $event): void
    {
        if (!isset($event->getIds()[0])) {
            return;
        }

        $id = $event->getIds()[0];

        try {
            $this->client->pushOrder(
                $this->getConfigurationValue('subdomain'),
                $this->getConfigurationValue('connectionkey'),
                $id
            );
        } catch (Exception $e) {
            // Silently fail
        }
    }

    private function getConfigurationValue(string $type): string
    {
        $value = $this->configService->get(sprintf('PicqerExtendedIntegration.config.%s', $type));
        if (!is_string($value)) {
            throw new RuntimeException(sprintf('%s not set', ucfirst($type)));
        }

        return $value;
    }
}