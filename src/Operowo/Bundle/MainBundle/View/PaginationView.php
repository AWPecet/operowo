<?php

namespace Operowo\Bundle\MainBundle\View;

use Assert\Assertion;
use Operowo\Bundle\MainBundle\Entity\InstitutionsCriteria;
use Symfony\Component\HttpFoundation\Response;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Knp\Component\Pager\Paginator;
use JMS\DiExtraBundle\Annotation as DI;
use Operowo\Bundle\MainBundle\Model\PaginatedResult;

/**
 * @DI\Service(id="operowo.view.pagination")
 */
class PaginationView extends BaseView
{
    private $paginator;

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

        $paginatedResults = $this->getOption('paginated_model');
        /** @var $pagination SlidingPagination */
        $pagination = $this->paginator->paginate($paginatedResults->getItems(), $paginatedResults->getCurrentPage(), $paginatedResults->getMaxPerPage());
        $pagination->setTotalItemCount($paginatedResults->getCountAll());
        $pagination->setUsedRoute($this->getOption('route'));

        return $pagination->render();
    }

    protected function buildOptionsResolver()
    {
        $resolver = parent::buildOptionsResolver();

        $resolver->setRequired(array(
            'paginated_model',
            'route',
        ));

        $resolver->addAllowedTypes(array(
            'paginated_model' => 'Operowo\Bundle\MainBundle\Model\PaginatedResult',
            'route' => 'string'
        ));

        return $resolver;
    }
}