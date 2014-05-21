<?php

namespace Midnight\ImageBlockModule\Form;

use Midnight\CmsModule\Service\BlockTypeManagerInterface;
use Midnight\ImageBlockModule\ImageBlock;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ImageFormFactory implements FactoryInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    private $sl;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return ImageForm
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->sl = $serviceLocator;
        $config = $this->getBlockTypeManager()->getConfigFor(new ImageBlock());
        return new ImageForm($config);
    }

    /**
     * @return BlockTypeManagerInterface
     */
    private function getBlockTypeManager()
    {
        return $this->sl->get('cms.block_type_manager');
    }
}
