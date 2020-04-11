<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Push Bundle.
 * (c) Werbeagentur Dreibein GmbH
 */

namespace SimonReitinger\ContaoPushBundle\Controller\FrontendModule;

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\ModuleModel;
use Contao\Template;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class PushNotificationButton extends AbstractFrontendModuleController
{
    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * @var ParameterBagInterface
     */
    private $bag;

    public function __construct(TranslatorInterface $translator, ParameterBagInterface $bag)
    {
        $this->translator = $translator;
        $this->bag = $bag;
    }

    protected function getResponse(Template $template, ModuleModel $model, Request $request): ?Response
    {
        $publicKey = $this->bag->get('minishlink_web_push.auth')['VAPID']['publicKey'];

        if (!\is_array($GLOBALS['TL_BODY']) || !\array_key_exists('contao_push', $GLOBALS['TL_BODY'])) {
            $GLOBALS['TL_BODY']['contao_push_key'] = sprintf("<script>const applicationServerKey = '%s';</script>", $publicKey);
        }

        $GLOBALS['TL_BODY']['contao_push'] = Template::generateScriptTag('/bundles/contaopush/main.js');

        return $template->getResponse();
    }
}
