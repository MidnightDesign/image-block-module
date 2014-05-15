<?php

namespace Midnight\ImageBlockModule\InlineBlockOption;

use Midnight\CmsModule\Service\BlockTypeManagerInterface;
use Midnight\ImageBlockModule\ImageBlock;
use Zend\Mvc\Router\RouteStackInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class InlineOptionsProviderFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $provider = new InlineOptionsProvider();
        $provider->setConfig($this->getBlockTypeManager($serviceLocator)->getConfigFor(new ImageBlock()));
        $provider->setRouter($this->getRouter($serviceLocator));
        return $provider;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return RouteStackInterface
     */
    private function getRouter(ServiceLocatorInterface $serviceLocator)
    {
        return $serviceLocator->get('Router');
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return BlockTypeManagerInterface
     */
    private function getBlockTypeManager(ServiceLocatorInterface $serviceLocator)
    {
        return $serviceLocator->get('cms.block_type_manager');
    }
}
