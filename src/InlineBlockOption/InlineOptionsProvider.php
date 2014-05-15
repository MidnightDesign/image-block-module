<?php

namespace Midnight\ImageBlockModule\InlineBlockOption;

use DoctrineMongoODMModule\Proxy\__CG__\Midnight\ImageBlockModule\ImageBlock;
use Midnight\Block\BlockInterface;
use Midnight\CmsModule\InlineBlockOption\DefaultInlineOptionsProvider;
use Midnight\CmsModule\InlineBlockOption\Option;
use Midnight\CmsModule\InlineBlockOption\OptionInterface;
use Midnight\Page\PageInterface;

class InlineOptionsProvider extends DefaultInlineOptionsProvider
{
    /**
     * @var
     */
    private $config = array();

    /**
     * @param BlockInterface $block
     * @param PageInterface  $page
     *
     * @return OptionInterface[]
     */
    public function getOptions(BlockInterface $block, PageInterface $page)
    {
        if (!$block instanceof ImageBlock) {
            throw new \RuntimeException('Not an ImageBlock.');
        }

        $options = parent::getOptions($block, $page);

        $styles = array();
        if (!empty($this->config) && !empty($this->config['styles'])) {
            $styles = $this->config['styles'];
        }

        foreach ($styles as $key => $label) {
            $option = new Option();
            if ($block->getClass() === $key) {
                continue;
            }
            $option->setHref($this->router->assemble(
                array(
                    'block_id' => $block->getId(),
                    'class' => $key,
                ),
                array('name' => 'zfcadmin/cms/set_image_block_class')
            ));
            $option->setLabel($label);
            $options[] = $option;
        }

        return $options;
    }

    /**
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }
} 
