<?php

namespace Operowo\Bundle\MainBundle\View;

use Operowo\Bundle\MainBundle\Entity\InstitutionsCriteria;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseView
{

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
}