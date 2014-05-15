<?php

namespace Midnight\ImageBlockModule\View\Helper;

use Zend\View\Helper\AbstractHelper;

class ImageBlock extends AbstractHelper
{
    public function __invoke(\Midnight\ImageBlockModule\ImageBlock $block)
    {
        $view = $this->getView();
        $headLink = $view->plugin('headLink');
        $basePath = $view->plugin('basePath');
        $headLink->appendStylesheet($basePath('css/image/image.css'));
        return sprintf(
            '<img src="%s" class="image-block %s" />',
            str_replace('data/uploads/cms', '', $block->getFile()),
            $block->getClass()
        );
    }

} 
