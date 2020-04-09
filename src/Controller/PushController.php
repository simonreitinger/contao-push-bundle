<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Push Bundle.
 * (c) Werbeagentur Dreibein GmbH
 */

namespace SimonReitinger\ContaoPushBundle\Controller;

use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;
use Psr\Log\LoggerInterface;
use SimonReitinger\ContaoPushBundle\Model\PushModel;
use SimonReitinger\ContaoPushBundle\Model\PushProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PushController extends AbstractController
{
    /**
     * @var PushProvider
     */
    private $provider;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(PushProvider $provider, LoggerInterface $logger)
    {
        $this->provider = $provider;
        $this->logger = $logger;
    }

    /**
     * @Route("/_push/subscription", name="push_subscription", defaults={"_scope"="frontend", "_token_check"=false})
     */
    public function handleSubscription(Request $request)
    {
        $payload = json_decode($request->getContent(), true);

        if (!$payload) {
            return new Response('', 400);
        }

        /** @var PushModel $push */
        $push = $this->provider->getPushByAuthToken($payload['authToken']);
        $subscription = Subscription::create($payload);

        // TODO logs

        switch ($request->getMethod()) {
            case 'POST':
                $push = new PushModel();
                $this->updatePush($push, $subscription);
                break;
            case 'PUT':
                if ($push) {
                    $this->updatePush($push, $subscription);
                }
                break;
            case 'DELETE':
                $push->delete();
                break;
        }

        return new Response('', Response::HTTP_OK);
    }

    private function updatePush(PushModel $push, Subscription $subscription)
    {
        $push->setAuthToken($subscription->getAuthToken());
        $push->setContentEncoding($subscription->getContentEncoding());
        $push->setEndpoint($subscription->getEndpoint());
        $push->setPublicKey($subscription->getPublicKey());

        $push->save();
    }
}
