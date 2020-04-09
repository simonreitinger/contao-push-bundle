<?php

namespace SimonReitinger\ContaoPushBundle\Controller\FrontendModule;

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\ModuleModel;
use Contao\Template;
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
     * PushNotificationButton constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    protected function getResponse(Template $template, ModuleModel $model, Request $request): ?Response
    {
        $GLOBALS['TL_BODY']['push'] = Template::generateScriptTag('/bundles/contaopush/main.min.js');

        $template->enableText = $this->translator->trans('enable', [], 'contao_push');
        $template->disableText = $this->translator->trans('disable', [], 'contao_push');

        return $template->getResponse();
    }
}
