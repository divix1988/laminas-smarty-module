<?php
namespace Smarty\Service\PluginProxy;

use Smarty\Plugin\CycleBlockPluginInterface;

class CycleBlockProxy
{
    /**
     * @var IfBlockPluginInterface
     */
    private $plugin;

    /**
     * @param IfBlockPluginInterface $plugin
     */
    public function __construct(CycleBlockPluginInterface $plugin)
    {
        $this->plugin = $plugin;
    }

    public function __invoke(array $params, $content, $smarty, &$repeat)
    {
        if (is_null($content)) {
            return $this->plugin->init($params, $smarty);
        }
        if (!$this->plugin->isValid($params, $smarty)) {
            $repeat = false;
            return $this->plugin->end($params, $smarty);
        }
        $repeat = true;
        return $this->plugin->prepareIteration($params, $content, $smarty);
    }
}
