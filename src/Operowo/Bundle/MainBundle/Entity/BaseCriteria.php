<?php

namespace Operowo\Bundle\MainBundle\Entity;

use Assert\Assertion;

class BaseCriteria
{
    const ASC  = 'asc';
    const DESC = 'desc';

    private $currentPage;
    private $maxPerPage;
    private $sortProperty;
    private $sortDirection;

    public function __construct($currentPage = 1, $maxPerPage = 10)
    {
        $this->setCurrentPage($currentPage);
        $this->setMaxPerPage($maxPerPage);
    }

    public function getSortables()
    {
        return array();
    }

    public function setSort($sortProperty, $sortDirection)
    {
        Assertion::string($sortProperty, 'Property for sort must be string');
        Assertion::string($sortDirection, 'Sort direction must be string');
        $sortDirection = strtolower($sortDirection);
        Assertion::inArray($sortProperty, $this->getSortables(), sprintf('Cannot sort with given "%s" property', $sortProperty));
        Assertion::inArray($sortDirection, array('asc', 'desc'), 'Sort direction must be ASC or DESC');

        $this->sortProperty = $sortProperty;
        $this->sortDirection = $sortDirection;
    }

    public function getSortDirection()
    {
        return $this->sortDirection;
    }

    public function getSortProperty()
    {
        return $this->sortProperty;
    }

    public function setCurrentPage($currentPage)
    {
        Assertion::integerish($currentPage, 'Current page should be integer');
        $this->currentPage = $currentPage;
    }

    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    public function setMaxPerPage($maxPerPage)
    {
        Assertion::integerish($maxPerPage, 'Max per page should be integer');
        $this->maxPerPage = $maxPerPage;
    }

    public function getMaxPerPage()
    {
        return $this->maxPerPage;
    }

    public function toArray()
    {
        $array = array();

        if ($this->getCurrentPage()) {
            $array['page'] = $this->getCurrentPage();
        }

        return $array;
    }
}
