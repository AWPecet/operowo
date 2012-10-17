<?php

namespace Operowo\Bundle\MainBundle\View;

use Assert\Assertion;
use Operowo\Bundle\MainBundle\Entity\InstitutionsCriteria;
use Symfony\Component\HttpFoundation\Response;
use Operowo\Bundle\MainBundle\Entity\BaseCriteria;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Knp\Component\Pager\Paginator;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service(id="operowo.view.pagination")
 */
class PaginationView extends BaseView
{
    private $paginator;

    /**
     * @var BaseCriteria
     */
    private $criteria;

    private $target;
    private $totalCount;
    private $route;

    /**
     * @DI\InjectParams({
     *      "paginator" = @DI\Inject("knp_paginator"),
     * })
     *
     * @param \Knp\Component\Pager\Paginator $paginator
     */
    public function __construct(Paginator $paginator)
    {
        $this->paginator = $paginator;
    }

    public function toString()
    {
        Assertion::notNull($this->getTarget(), 'Target is not set');
        Assertion::notNull($this->getCriteria(), 'Criteria is not set');
        Assertion::notNull($this->getTotalCount(), 'Total count is not set');

        $criteria = $this->getCriteria();
        /** @var $pagination SlidingPagination */
        $pagination = $this->paginator->paginate($this->getTarget(), $criteria->getCurrentPage(), $criteria->getMaxPerPage());
        $pagination->setTotalItemCount($this->getTotalCount());
        if ($this->getRoute()) {
            $pagination->setUsedRoute($this->getRoute());
        }

        return $pagination->render();
    }

    public function setCriteria($criteria)
    {
        $this->criteria = $criteria;
        return $this;
    }

    public function getCriteria()
    {
        return $this->criteria;
    }

    public function setTarget($target)
    {
        $this->target = $target;
        return $this;
    }

    public function getTarget()
    {
        return $this->target;
    }

    public function setTotalCount($totalCount)
    {
        $this->totalCount = $totalCount;
        return $this;
    }

    public function getTotalCount()
    {
        return $this->totalCount;
    }

    public function setRoute($route)
    {
        $this->route = $route;
        return $this;
    }

    public function getRoute()
    {
        return $this->route;
    }
}