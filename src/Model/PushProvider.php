<?php

namespace SimonReitinger\ContaoPushBundle\Model;

/**
 * PushProvider is an abstraction layer for Contao models.
 */
class PushProvider
{
    public function getPushByAuthToken($authToken)
    {
        return PushModel::findOneBy('authToken', $authToken);
    }

    public function getAll()
    {
        return PushModel::findAll();
    }
}
