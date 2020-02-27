<?php
namespace Smarty\Plugin;

use Smarty;

/**
 * Interface to detect if a class is Smarty CycleBlock plugin.
 */
interface CycleBlockPluginInterface extends PluginInterface
{
    /**
     * Call before cycle.
     *
     * @param array $params
     * @param Smarty $smarty
     *
     * @return string|null
     */
    public function init(array $params, $smarty);

    /**
     * Call before iteration.
     *
     * @param array $params
     * @param Smarty $smarty
     *
     * @return bool true - if the current iteration is valid.
     */
    public function isValid(array $params, $smarty);
    
    /**
     * Attention: move the pointer to this method.
     *
     * @param array $params
     * @param string $content
     * @param Smarty $smarty
     *
     * @return string Content of the current iteration.
     */
    public function prepareIteration(array $params, $content, $smarty);

    /**
     * Call after cycle.
     *
     * @param array $params
     * @param Smarty $smarty
     *
     * @return string|null
     */
    public function end(array $params, $smarty);
}
