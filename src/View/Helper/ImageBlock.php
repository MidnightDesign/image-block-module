<?php

namespace Midnight\ImageBlockModule\View\Helper;

use Midnight\ImageBlockModule\Form\ImageForm;
use Midnight\Page\PageInterface;
use Zend\Form\View\Helper\Form;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Url;
use ZfcRbac\Service\AuthorizationServiceInterface;

class ImageBlock extends AbstractHelper
{
    /**
     * @var AuthorizationServiceInterface
     */
    private $authorizationService;
    /**
     * @var ImageForm
     */
    private $form;

    /**
     * @param \Midnight\ImageBlockModule\ImageBlock $block
     * @param \Midnight\Page\PageInterface          $page
     *
     * @return string
     */
    public function __invoke(\Midnight\ImageBlockModule\ImageBlock $block, PageInterface $page = null)
    {
        $view = $this->getView();
        $headLink = $view->plugin('headLink');
        $basePath = $view->plugin('basePath');
        $headLink->appendStylesheet($basePath('css/image/image.css'));
        if (!$block->getFile()) {
            if (!$this->authorizationService->isGranted('cms.block.image.edit')) {
                return '';
            }
            $formHelper = $this->getFormHelper();
            $form = $this->form;
            $form->bind($block);
            $urlHelper = $this->getUrlHelper();
            $params = array('block_id' => $block->getId());
            $query = array();
            if (null !== $page) {
                $query['page_id'] = $page->getId();
            }
            $form->setAttribute('action', $urlHelper('zfcadmin/cms/block/edit', $params, array('query' => $query)));
            return $formHelper($form);
        }
        return sprintf(
            '<img src="%s" class="image-block %s" />',
            str_replace('data/uploads/cms', '', $block->getFile()),
            $block->getClass()
        );
    }

    /**
     * @param AuthorizationServiceInterface $authorizationService
     */
    public function setAuthorizationService(AuthorizationServiceInterface $authorizationService)
    {
        $this->authorizationService = $authorizationService;
    }

    /**
     * @param ImageForm $form
     */
    public function setForm($form)
    {
        $this->form = $form;
    }

    /**
     * @return Form
     */
    private function getFormHelper()
    {
        return $this->getView()->plugin('form');
    }

    /**
     * @return Url
     */
    private function getUrlHelper()
    {
        return $this->getView()->plugin('url');
    }

} 
