<?php
namespace Smarty\Service;

use Laminas\ServiceManager\AbstractPluginManager;
use Smarty\Plugin\PluginInterface;

class PluginManager extends AbstractPluginManager
{
    protected $instanceOf = PluginInterface::class;
}
