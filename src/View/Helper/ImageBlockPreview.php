<?php

namespace Midnight\ImageBlockModule\View\Helper;

use Midnight\ImageBlockModule\ImageBlock;
use Zend\View\Helper\AbstractHelper;

class ImageBlockPreview extends AbstractHelper
{
    public function __invoke(ImageBlock $block)
    {
        $view = $this->getView();
        $headLink = $view->plugin('headLink');
        $basePath = $view->plugin('basePath');
        $headLink->appendStylesheet($basePath('css/image/image.css'));
        return sprintf('<img src="%s" class="image-block" />', str_replace('data/uploads/cms', '', $block->getFile()));
    }
} 
