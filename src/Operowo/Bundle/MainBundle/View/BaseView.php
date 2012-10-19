<?php

namespace Operowo\Bundle\MainBundle\View;

use Operowo\Bundle\MainBundle\Entity\InstitutionsCriteria;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class BaseView
{
    private $options = array();
    private $bound = false;

    public function bind(array $options)
    {
        if ($this->bound) {
            throw new \RuntimeException("Can not call bind() method on bound view");
        }
        $resolver = $this->buildOptionsResolver();
        $this->options = $resolver->resolve($options);
        $this->bound = true;
    }

    public function getOption($name)
    {
        $this->assureIsBound(__METHOD__);
        if (!isset($this->options[$name])) {
            throw new \OutOfBoundsException(sprintf('Option with given "%s" name does not exist', $name));
        }

        return $this->options[$name];
    }

    /**
     * @return OptionsResolver
     */
    protected function buildOptionsResolver()
    {
        $resolver = new OptionsResolver();
        return $resolver;
    }

    public function toResponse(Response $response = null)
    {
        if ($response === null) {
            $response = new Response();
        }

        $response->setContent($this->toString());

        return $response;
    }

    public function __toString()
    {
        return $this->toString();
    }

    abstract public function toString();

    protected function assureIsBound($method)
    {
        if (!$this->bound) {
            throw new \RuntimeException(sprintf('Can not call "%s" method on unbound view', $method));
        }
    }
}