<?php

namespace Midnight\ImageBlockModule\Controller;

use Midnight\ImageBlockModule\Form\ImageForm;
use Midnight\ImageBlockModule\ImageBlock;
use Midnight\CmsModule\Controller\AbstractCmsController;
use Midnight\CmsModule\Controller\Block\BlockControllerInterface;
use Midnight\Page\PageInterface;
use Zend\Form\FormInterface;
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
        $form = new ImageForm();
        $block = new ImageBlock();
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
                $pageId = $this->params()->fromQuery('page_id');
                if ($pageId) {
                    $pageStorage = $this->getPageStorage();
                    $page = $pageStorage->load($pageId);
                    $page->addBlock($block);
                    $pageStorage->save($page);
                    return $this->redirect()->toRoute('zfcadmin/cms/page/edit', array('page_id' => $page->getId()));
                } else {
                    throw new \Exception('Not implemented.');
                }
            }
        }

        $vm = new ViewModel(array('form' => $form));
        $vm->setTemplate('image/image/create.phtml');
        return $vm;
    }

    public function editAction()
    {
        $form = new ImageForm();
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
                if ($page) {
                    $pageStorage = $this->getPageStorage();
                    $pageStorage->save($page);
                    return $this->redirect()->toRoute('zfcadmin/cms/page/edit', array('page_id' => $page->getId()));
                } else {
                    throw new \Exception('Not implemented.');
                }
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
}
