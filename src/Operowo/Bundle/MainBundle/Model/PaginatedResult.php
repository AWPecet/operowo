<?php

namespace Operowo\Bundle\MainBundle\Model;

use Operowo\Bundle\MainBundle\Entity\BaseCriteria;

class PaginatedResult
{
    private $items;
    private $currentPage;
    private $pagesCount;
    private $countAll;
    private $maxPerPage;

    public function __construct($items, $countAll, BaseCriteria $criteria)
    {
        $this->items = $items;
        $this->currentPage = $criteria->getCurrentPage();
        $this->countAll = $countAll;
        $this->maxPerPage = $criteria->getMaxPerPage();
        $this->pagesCount = ceil($this->countAll / $this->maxPerPage);
    }

    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function getPagesCount()
    {
        return $this->pagesCount;
    }

    public function getCountAll()
    {
        return $this->countAll;
    }

    public function getMaxPerPage()
    {
        return $this->maxPerPage;
    }
}