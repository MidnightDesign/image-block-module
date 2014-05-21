<?php

namespace Midnight\ImageBlockModule\Form;

use Zend\Filter\File\RenameUpload;
use Zend\Form\Element\File;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Validator\File\UploadFile;

class ImageForm extends Form
{
    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        parent::__construct();

        $this->setHydrator(new ClassMethods());

        $this->add(new File('image', array('label' => 'Image')));

        if (!empty($config['styles'])) {
            $this->add(array(
                'name' => 'class',
                'type' => 'Radio',
                'options' => array(
                    'label' => 'Style',
                    'value_options' => $config['styles'],
                )
            ));
        }

        $this->add(array(
            'name' => 'position',
            'type' => 'Hidden',
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Upload',
            ),
        ));

        $input = $this->getInputFilter()->get('image');
        $input->getValidatorChain()->attach(new UploadFile());
        $input->getFilterChain()->attach(new RenameUpload(array(
            'target' => 'data/uploads/cms/image-block',
        )));
    }

} 
