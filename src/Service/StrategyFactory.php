<?php
namespace Smarty\Service;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Smarty\View\Renderer;
use Smarty\View\Strategy;

class StrategyFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $renderer = $container->get(Renderer::class);
        $strategy = new Strategy($renderer);
        return $strategy;
    }
}
