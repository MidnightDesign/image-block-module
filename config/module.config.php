<?php

namespace Midnight\ImageBlockModule;

return array(
    'cms' => array(
        'blocks' => array(
            'image' => array(
                'name' => 'Bild',
                'class' => 'Midnight\ImageBlockModule\ImageBlock',
                'controller' => __NAMESPACE__ . '\Controller\ImageController',
                'renderer' => 'imageBlock',
                'preview_renderer' => 'imageBlockPreview',
                'inline_options_provider' => __NAMESPACE__ . '\InlineBlockOption\InlineOptionsProvider',
                'styles' => array(
                    'block' => 'Block',
                    'float' => 'Float',
                ),
                'inline_container_class' => 'image-block',
            )
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            __NAMESPACE__ . '\InlineBlockOption\InlineOptionsProvider' => __NAMESPACE__ . '\InlineBlockOption\InlineOptionsProviderFactory',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'imageBlock' => __NAMESPACE__ . '\View\Helper\ImageBlock',
            'imageBlockPreview' => __NAMESPACE__ . '\View\Helper\ImageBlockPreview',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            __NAMESPACE__ . '\Controller\ImageController' => __NAMESPACE__ . '\Controller\ImageController',
            __NAMESPACE__ . '\ImageBlock' => __NAMESPACE__ . '\Controller\ImageController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            dirname(__DIR__) . '/view',
        ),
    ),
    'asset_manager' => array(
        'resolver_configs' => array(
            'paths' => array(
                dirname(__DIR__) . '/public/',
                'data/uploads/cms/',
            ),
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ => array(
                'class' => 'Doctrine\ODM\MongoDB\Mapping\Driver\XmlDriver',
                'cache' => 'array',
                'paths' => array(dirname(__DIR__) . '/mapping'),
            ),
            'odm_default' => array(
                'drivers' => array(
                    'Midnight\ImageBlock' => __NAMESPACE__,
                ),
            ),
        ),
    ),
    'router' => array(
        'routes' => array(
            'zfcadmin' => array(
                'child_routes' => array(
                    'cms' => array(
                        'child_routes' => array(
                            'set_image_block_class' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/set-image-block-class/:block_id/:class',
                                    'defaults' => array(
                                        'controller' => __NAMESPACE__ . '\ImageBlock',
                                        'action' => 'set-class'
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
