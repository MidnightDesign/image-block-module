<?php

namespace Midnight\ImageBlockModule\View\Helper;

use Midnight\ImageBlockModule\Form\ImageForm;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\Url;
use ZfcRbac\Service\AuthorizationServiceInterface;

class ImageBlockFactory implements FactoryInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    private $sl;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return ImageBlock
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->sl = $serviceLocator;
        $imageBlock = new ImageBlock();
        $imageBlock->setAuthorizationService($this->getAuthorizationService());
        $imageBlock->setForm($this->getForm());
        return $imageBlock;
    }

    /**
     * @return AuthorizationServiceInterface
     */
    private function getAuthorizationService()
    {
        return $this->sl->getServiceLocator()->get('ZfcRbac\Service\AuthorizationService');
    }

    /**
     * @return ImageForm
     */
    private function getForm()
    {
        return $this->sl->getServiceLocator()->get('Midnight\ImageBlockModule\Form\ImageForm');
    }
}
