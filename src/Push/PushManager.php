<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Push Bundle.
 * (c) Werbeagentur Dreibein GmbH
 */

namespace SimonReitinger\ContaoPushBundle\Push;

use Contao\CoreBundle\Monolog\ContaoContext;
use Doctrine\ORM\EntityManagerInterface;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use SimonReitinger\ContaoPushBundle\Entity\Push;

class PushManager
{
    /**
     * @var WebPush
     */
    private $push;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * PushManager constructor.
     */
    public function __construct(WebPush $push, EntityManagerInterface $em, LoggerInterface $logger)
    {
        $this->push = $push;
        $this->em = $em;
        $this->logger = $logger;
    }

    public function sendNotification(string $title, string $body, string $url): void
    {
        $subscriptions = $this->em->getRepository(Push::class)->findAll();

        $payload = \json_encode([
            'title' => $title,
            'body' => $body,
            'data' => [
                'url' => $url,
            ],
        ]);

        /** @var array<Push> $sub */
        foreach ($subscriptions as $sub) {
            $subscription = Subscription::create($sub->toArray());
            $this->push->sendNotification($subscription, $payload);
        }

        $this->flushNotifications();
    }

    private function flushNotifications(): void
    {
        $context = [
            'contao' => new ContaoContext(__METHOD__, ContaoContext::GENERAL),
        ];

        foreach ($this->push->flush() as $report) {
            $endpoint = (string) $report->getRequest()->getUri();

            if ($report->isSuccess()) {
                $this->logger->log(LogLevel::INFO, "Message sent successfully for subscription {$endpoint}.", $context);
            } else {
                $this->logger->log(LogLevel::ERROR, "Message failed to sent for subscription {$endpoint}: {$report->getReason()}", $context);
            }
        }
    }
}
