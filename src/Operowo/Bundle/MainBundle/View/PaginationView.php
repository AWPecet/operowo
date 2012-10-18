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
        $this->assureIsBound(__METHOD__);

        $criteria = $this->getOption('criteria');
        /** @var $pagination SlidingPagination */
        $pagination = $this->paginator->paginate($this->getOption('target'), $criteria->getCurrentPage(), $criteria->getMaxPerPage());
        $pagination->setTotalItemCount($this->getOption('total_count'));
        $pagination->setUsedRoute($this->getOption('route'));

        return $pagination->render();
    }

    protected function buildOptionsResolver()
    {
        $resolver = parent::buildOptionsResolver();

        $resolver->setRequired(array(
            'criteria',
            'total_count',
            'route',
            'target'
        ));

        $resolver->addAllowedTypes(array(
            'criteria' => 'Operowo\Bundle\MainBundle\Entity\BaseCriteria',
            'total_count' => 'int',
            'route' => 'string',
            'target' => 'array'
        ));

        return $resolver;
    }
}