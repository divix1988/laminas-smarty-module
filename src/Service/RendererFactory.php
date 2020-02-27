<?php
namespace Smarty\Service;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Smarty\View\Renderer;
use Smarty;
use Laminas\View\HelperPluginManager;

class RendererFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Configuration');
        $config = $config['smarty'];

        /** @var $pathResolver \Laminas\View\Resolver\TemplatePathStack */
        $pathResolver = clone $container->get('ViewTemplatePathStack');
        $pathResolver->setDefaultSuffix($config['suffix']);

        /** @var $resolver \Laminas\View\Resolver\AggregateResolver */
        $resolver = $container->get('ViewResolver');
        $resolver->attach($pathResolver);

        $engine = new Smarty();
        $engine->setCompileDir($config['compile_dir']);
        $engine->setEscapeHtml($config['escape_html']);
        $engine->setTemplateDir($pathResolver->getPaths()->toArray());
        $engine->setCaching($config['caching']);
        $engine->setCacheDir($config['cache_dir']);
        $engine->addPluginsDir($config['plugins_dir']);

        if (file_exists($config['config_file'])) {
            $engine->configLoad($config['config_file']);
        }

        /** @var HelperPluginManager $helpers */
        $helpers = $container->get('ViewHelperManager');

        $renderer = new Renderer();
        $renderer->setEngine($engine);
        $renderer->setSuffix($config['suffix']);
        $renderer->setResolver($resolver);
        $renderer->setHelperPluginManager($helpers);

        return $renderer;
    }
}
