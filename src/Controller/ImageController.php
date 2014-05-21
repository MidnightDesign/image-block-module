<?php

namespace Midnight\ImageBlockModule\Controller;

use Midnight\CmsModule\Controller\AbstractCmsController;
use Midnight\CmsModule\Controller\Block\BlockControllerInterface;
use Midnight\CmsModule\Service\BlockTypeManagerInterface;
use Midnight\ImageBlockModule\Form\ImageForm;
use Midnight\ImageBlockModule\ImageBlock;
use Midnight\Page\PageInterface;
use Zend\Form\FormInterface;
use Zend\Http\Header\Referer;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\Response;
use Zend\View\Model\ViewModel;

/**
 * Class ImageController
 * @package Midnight\ImageBlockModule\Controller
 *
 * @method Request getRequest()
 */
class ImageController extends AbstractCmsController implements BlockControllerInterface
{
    public function createAction()
    {
        $form = new ImageForm($this->getImageBlockConfig());

        $position = $this->params()->fromRoute('position');
        $form->get('position')->setValue($position);

        $block = new ImageBlock();
        $form->bind($block);

        $pageId = $this->params()->fromQuery('page_id');
        $pageStorage = $this->getPageStorage();
        $page = $pageStorage->load($pageId);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            $form->setData($post);
            if ($form->isValid()) {
                $data = $form->getData(FormInterface::VALUES_AS_ARRAY);
                $block = $form->getObject();
                $block->setFile($data['image']['tmp_name']);
                if ($pageId) {
                    $page->addBlock($block, $data['position']);
                    $pageStorage->save($page);
                    return $this->redirect()->toRoute('zfcadmin/cms/page/edit', array('page_id' => $page->getId()));
                } else {
                    throw new \Exception('Not implemented.');
                }
            }
        } else {
            $page->addBlock($block, $position);
            $pageStorage->save($page);
            return $this->redirect()->toRoute('cms_page', array('slug' => $page->getSlug()));
        }

        $vm = new ViewModel(array('form' => $form));
        $vm->setTemplate('image/image/create.phtml');
        return $vm;
    }

    public function editAction()
    {
        $form = new ImageForm($this->getImageBlockConfig());
        /** @var $block ImageBlock */
        $block = $this->params()->fromRoute('block');
        /** @var $page PageInterface */
        $page = $this->params()->fromRoute('page');
        $action = $this->url()->fromRoute(
            'zfcadmin/cms/block/edit',
            array('block_id' => $block->getId()),
            array('query' => array('page_id' => $page->getId()))
        );
        $form->setAttribute('action', $action);
        $form->bind($block);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($post);
            if ($form->isValid()) {
                $data = $form->getData(FormInterface::VALUES_AS_ARRAY);
                $block = $form->getObject();
                $block->setFile($data['image']['tmp_name']);
                $this->getBlockStorage()->save($block);
                return $this->redirect()->toRoute('zfcadmin/cms/page/edit', array('page_id' => $page->getId()));
            }
        }

        $vm = new ViewModel(array('form' => $form));
        if ($request->isXmlHttpRequest()) {
            $vm->setTerminal(true);
            $response = $this->getResponse();
            if ($response instanceof Response) {
                $response->getHeaders()->addHeaderLine('Content-type: text/fragment+html');
            }
        }
        $vm->setTemplate('image/image/edit.phtml');
        return $vm;
    }

    public function setClassAction()
    {
        $storage = $this->getBlockStorage();
        $block = $storage->load($this->params()->fromRoute('block_id'));
        if (!$block instanceof ImageBlock) {
            throw new \RuntimeException(sprintf(
                'Expected an ImageBlock, got %s.',
                is_object($block) ? get_class($block) : gettype($block)
            ));
        }
        $class = $this->params()->fromRoute('class');
        $block->setClass($class);
        $storage->save($block);

        $request = $this->getRequest();
        if ($request instanceof \Zend\Http\Request) {
            $referer = $request->getHeader('Referer');
            if ($referer && $referer instanceof Referer) {
                return $this->redirect()->toUrl((string)$referer->uri());
            }
        }
    }

    /**
     * @return array
     */
    private function getImageBlockConfig()
    {
        return $this->getBlockTypeManager()->getConfigFor(new ImageBlock());
    }

    /**
     * @return BlockTypeManagerInterface
     */
    private function getBlockTypeManager()
    {
        return $this->getServiceLocator()->get('cms.block_type_manager');
    }
}
