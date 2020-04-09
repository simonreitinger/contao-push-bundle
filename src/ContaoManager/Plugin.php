<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Push Bundle.
 * (c) Werbeagentur Dreibein GmbH
 */

namespace SimonReitinger\ContaoPushBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Config\ConfigPluginInterface;
use Contao\ManagerPlugin\Routing\RoutingPluginInterface;
use Minishlink\Bundle\WebPushBundle\MinishlinkWebPushBundle;
use SimonReitinger\ContaoPushBundle\ContaoPushBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class Plugin implements BundlePluginInterface, ConfigPluginInterface, RoutingPluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(MinishlinkWebPushBundle::class),
            BundleConfig::create(ContaoPushBundle::class)
                ->setLoadAfter([
                    ContaoCoreBundle::class,
                    MinishlinkWebPushBundle::class,
                ]),
        ];
    }

    /**
     * @inheritDoc
     */
    public function registerContainerConfiguration(LoaderInterface $loader, array $managerConfig)
    {
        $loader->load(__DIR__ . '/../Resources/config/services.yml');
    }

    /**
     * @inheritDoc
     */
    public function getRouteCollection(LoaderResolverInterface $resolver, KernelInterface $kernel)
    {
        $file = __DIR__ . '/../Resources/config/routing.yml';

        return $resolver
            ->resolve($file)
            ->load($file)
        ;
    }
}
