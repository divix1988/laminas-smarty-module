<?php
namespace Smarty\Plugin;

/**
 * Interface to detect if a class is Smarty Modifier plugin.
 */
interface ModifierPluginInterface extends PluginInterface
{
    /**
     * @param mixed $value
     * @param mixed ...
     *
     * @return mixed
     */
    public function modify($value);
}
