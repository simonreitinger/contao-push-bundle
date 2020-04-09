<?php

namespace SimonReitinger\ContaoPushBundle\DataContainer;

use Contao\DataContainer;
use Contao\Model;
use Contao\NewsModel;
use SimonReitinger\ContaoPushBundle\Push\PushManager;
use Symfony\Component\HttpFoundation\RequestStack;

class News
{
    /**
     * @var PushManager
     */
    private $manager;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * News constructor.
     * @param PushManager $manager
     */
    public function __construct(PushManager $manager, RequestStack $requestStack)
    {
        $this->manager = $manager;
        $this->requestStack = $requestStack;
    }

    public function onLoad($dc)
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$request->query->get('sendPush')) {
            return;
        }

        $model = $this->getModel($dc->id, $dc->table);

        if (!$model) {
            return;
        }

        $title = $model->headline;
        $body = $model->subheadline;

        $this->manager->sendNotification($title, $body);
    }

    public function getModel($id, $table)
    {
        return (Model::getClassFromTable($table))::findByPk($id);
    }
}
