<?php

namespace SimonReitinger\ContaoPushBundle\Push;

use Contao\CoreBundle\Monolog\ContaoContext;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use SimonReitinger\ContaoPushBundle\Model\PushModel;
use SimonReitinger\ContaoPushBundle\Model\PushProvider;

class PushManager
{
    /**
     * @var WebPush
     */
    private $push;

    /**
     * @var PushProvider
     */
    private $provider;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * PushManager constructor.
     */
    public function __construct(WebPush $push, PushProvider $provider, LoggerInterface $logger)
    {
        $this->push = $push;
        $this->provider = $provider;
        $this->logger = $logger;
    }

    public function sendNotification(string $title, string $body)
    {
        $subscriptions = $this->provider->getAll();

        $payload = \json_encode([
            'title' => $title,
            'body' => $body,
        ]);

        /** @var array<PushModel> $sub */
        foreach ($subscriptions as $sub) {
            $subscription = Subscription::create($sub->row());
            $this->push->sendNotification($subscription, $payload);
        }

        $this->flushNotifications();
    }

    private function flushNotifications()
    {
        $context = [
            'contao' => new ContaoContext(__METHOD__, ContaoContext::GENERAL)
        ];

        foreach ($this->push->flush() as $report) {
            $endpoint = (string)$report->getRequest()->getUri();

            if ($report->isSuccess()) {
                $this->logger->log(LogLevel::INFO, "Message sent successfully for subscription {$endpoint}.", $context);
            } else {
                $this->logger->log(LogLevel::ERROR, "Message failed to sent for subscription {$endpoint}: {$report->getReason()}", $context);
            }
        }
    }
}
