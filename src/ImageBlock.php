<?php

namespace Midnight\ImageBlockModule;

use Midnight\Block\AbstractBlock;

class ImageBlock extends AbstractBlock
{
    /**
     * @var string
     */
    private $file;
    /**
     * @var string
     */
    private $class = 'block';

    /**
     * @param string $file
     */
    public function __construct($file = null)
    {
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param string $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }
} 
