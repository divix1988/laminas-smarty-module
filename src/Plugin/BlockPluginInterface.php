<?php
namespace Smarty\Plugin;

use Smarty;

/**
 * Interface to detect if a class is Smarty Block plugin.
 */
interface BlockPluginInterface extends PluginInterface
{
    /**
     * @param array $params
     * @param string $content
     * @param Smarty $smarty
     * @param bool $repeat
     *
     * @return string
     */
    public function prepare(array $params, $content, $smarty, &$repeat);
}
