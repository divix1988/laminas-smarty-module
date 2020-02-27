<?php
namespace Smarty\Plugin;

use Smarty;

/**
 * Interface to detect if a class is Smarty IfBlock plugin.
 */
interface IfBlockPluginInterface extends PluginInterface
{
    /**
     * @param array $params
     * @param Smarty $smarty
     *
     * @return bool
     */
    public function checkCondition(array $params, $smarty);

    /**
     * @param array $params
     * @param string $content
     * @param Smarty $smarty
     *
     * @return string Content, if the condition is true.
     */
    public function prepareTrue(array $params, $content, $smarty);

    /**
     * @param array $params
     * @param string $content
     * @param Smarty $smarty
     *
     * @return string Content, if the condition is false.
     */
    public function prepareFalse(array $params, $content, $smarty);
}
