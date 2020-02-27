<?php
namespace Smarty;

use Smarty\View\Strategy;
use Smarty\Service\StrategyFactory;
use Smarty\View\Renderer;
use Smarty\Service\RendererFactory;
use Smarty\Service\PluginManager;
use Smarty\Service\PluginManagerFactory;
use Smarty\Service\PluginDelegator;

return [
    'view_manager' => [
        'strategies' => [
            Strategy::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Strategy::class => StrategyFactory::class,
            Renderer::class => RendererFactory::class,
            PluginManager::class => PluginManagerFactory::class,
        ],
        'delegators' => [
            Renderer::class => [
                PluginDelegator::class,
            ],
        ],
    ],
    'smarty' => [
        'suffix' => 'tpl',
        'compile_dir' => getcwd() . '/data/smarty/templates_c',
        'config_file' => getcwd() . '/config/autoload/smarty.conf',
        'escape_html' => true,
        'caching' => false,
        'cache_dir' => getcwd() . '/data/smarty/cache',
        'plugins_dir' => getcwd() . '/data/smarty/plugins',
        'plugins' => [
            // Plugin manager configuration.
            'manager' => [
            ],
            // Plugins.
            // For example (MyFuncPlugin should be added in Plugin manager):
            //     'functions' => [
            //         'my_func' => MyFuncPlugin::class,
            //     ]
            // This config register MyFuncPlugin with "my_func" name in Smarty.
            'functions' => [
            ],
            'modifiers' => [
            ],
            'blocks' => [
            ],
            'if_blocks' => [
            ],
            'cycle_blocks' => [
            ],
        ],
    ],
];
