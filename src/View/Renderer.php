<?php

namespace Smarty\View;

use ArrayObject;
use RuntimeException;
use Smarty;
use Laminas\View\Exception\DomainException;
use Laminas\View\HelperPluginManager;
use Laminas\View\Model\ModelInterface;
use Laminas\View\Renderer\RendererInterface;
use Laminas\View\Resolver\ResolverInterface;

class Renderer implements RendererInterface
{
    /**
     * @var Smarty
     */
    protected $engine;

    /**
     * @var ResolverInterface
     */
    protected $resolver;

    /**
     * Template suffix for this renderer
     *
     * @var string
     */
    protected $suffix;

    /**
     * @var HelperPluginManager
     */
    protected $helper;

    /**
     * @param Smarty $engine
     */
    public function setEngine(Smarty $engine)
    {
        $this->engine = $engine;
        $this->engine->assign('this', $this);
    }

    /**
     * @return Smarty
     */
    public function getEngine()
    {
        return $this->engine;
    }

    /**
     * Set script resolver
     *
     * @param ResolverInterface $resolver
     *
     * @return $this
     */
    public function setResolver(ResolverInterface $resolver)
    {
        $this->resolver = $resolver;
        return $this;
    }

    /**
     * Processes a view script and returns the output.
     *
     * @param  string|Model $nameOrModel Either the template to use, or a
     *                                   ViewModel. The ViewModel must have the
     *                                   template as an option in order to be
     *                                   valid.
     * @param  null|array|Traversable $values Values to use when rendering. If none
     *                                provided, uses those in the composed
     *                                variables container.
     * @return string The script output.
     * @throws DomainException if a ViewModel is passed, but does not
     *                                   contain a template option.
     * @throws RuntimeException if the template cannot be rendered
     */
    public function render($nameOrModel, $values = null)
    {
        if ($nameOrModel instanceof ModelInterface) {
            $model       = $nameOrModel;
            $nameOrModel = $nameOrModel->getTemplate();
            if (empty($nameOrModel)) {
                throw new DomainException(
                    sprintf(
                        '%s: recieved View Model argument, but template is empty.',
                        __METHOD__
                    )
                );
            }
            $values = $model->getVariables();
            unset($model);
        }
        if (!($file = $this->resolver->resolve($nameOrModel))) {
            throw new RuntimeException(
                sprintf(
                    'Unable to find template "%s"; resolver could not resolve to a file',
                    $nameOrModel
                )
            );
        }

        if ($values instanceof ArrayObject) {
            $values = $values->getArrayCopy();
        }

        $smarty = $this->getEngine();
        $smarty->assign($values);

        $content = $smarty->fetch($file);

        return $content;
    }

    /**
     * @param $nameOrModel
     *
     * @return bool
     */
    public function canRender($nameOrModel)
    {
        if ($nameOrModel instanceof ModelInterface) {
            $nameOrModel = $nameOrModel->getTemplate();
        }
        $tpl = $this->resolver->resolve($nameOrModel);
        $ext = pathinfo($tpl, PATHINFO_EXTENSION);
        if ($tpl && $ext == $this->getSuffix()) {
            return true;
        }

        return false;
    }

    /**
     * @param $suffix
     */
    public function setSuffix($suffix)
    {
        $this->suffix = $suffix;
    }

    /**
     * @return string
     */
    public function getSuffix()
    {
        return $this->suffix;
    }

    /**
     * Set helper plugin manager instance
     *
     * @param HelperPluginManager $helper
     *
     * @return $this
     */
    public function setHelperPluginManager(HelperPluginManager $helper)
    {
        if (is_string($helper)) {
            if (!class_exists($helper)) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Invalid helper helpers class provided (%s)',
                        $helper
                    )
                );
            }
            $helper = new $helper(new ServiceManager());
        }
        if (!$helper instanceof HelperPluginManager) {
            throw new InvalidArgumentException(
                sprintf(
                    'Helper helpers must extend Zend\View\HelperPluginManager; got type "%s" instead',
                    (is_object($helper) ? get_class($helper) : gettype($helper))
                )
            );
        }
        $helper->setRenderer($this);
        $this->helper = $helper;

        return $this;
    }

    /**
     * @return HelperPluginManager|null
     */
    public function getHelperPluginManager()
    {
        if ($this->helper === null) {
            $this->setHelperPluginManager(new HelperPluginManager(new ServiceManager()));
        }

        return $this->helper;
    }

    /**
     * @param string     $name
     * @param array|null $options
     *
     * @return mixed
     */
    public function plugin($name, array $options = null)
    {
        return $this->getHelperPluginManager()->get($name, $options);
    }

    /**
     * @param string $method
     * @param array  $argv
     *
     * @return mixed
     */
    public function __call($method, $argv)
    {
        $plugin = $this->plugin($method);

        if (is_callable($plugin)) {
            return call_user_func_array($plugin, $argv);
        }

        return $plugin;
    }
}
