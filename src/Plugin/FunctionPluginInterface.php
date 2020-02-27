<?php
namespace Smarty\Plugin;

use Smarty;

/**
 * Interface to detect if a class is Smarty Function plugin.
 */
interface FunctionPluginInterface extends PluginInterface
{
    /**
     * @param array $params
     * @param Smarty $smarty
     *
     * @return mixed
     */
    public function run(array $params, $smarty);
}
