<?php
namespace Smarty\View;

use Laminas\EventManager\ListenerAggregateInterface;
use Laminas\EventManager\EventManagerInterface;
use Laminas\View\ViewEvent;
use Smarty\View\Renderer;

class Strategy implements ListenerAggregateInterface
{
    /**
     * @var callable
     */
    protected $listeners = [];
    /**
     * @var Renderer
     */
    protected $renderer;

    public function __construct(Renderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function attach(EventManagerInterface $events, $priority = 100)
    {
        $this->listeners[] = $events->attach(ViewEvent::EVENT_RENDERER, [$this, 'selectRenderer'], $priority);
        $this->listeners[] = $events->attach(ViewEvent::EVENT_RESPONSE, [$this, 'injectResponse'], $priority);
    }

    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            $events->detach($listener);
            unset($this->listeners[$index]);
        }
    }

    public function selectRenderer(ViewEvent $e)
    {
        if (!$this->renderer->canRender($e->getModel())) {
            return false;
        }
        return $this->renderer;
    }

    public function injectResponse(ViewEvent $e)
    {
        $renderer = $e->getRenderer();
        if ($renderer !== $this->renderer) {
            return false;
        }
        $result = $e->getResult();
        $response = $e->getResponse();
        $response->setContent($result);
    }
}
