<?php

namespace Operowo\Bundle\MainBundle\Entity;

use Assert\Assertion;

class InstitutionsCriteria extends BaseCriteria
{
    private $provinceIdFilter = array();

    public function filterByProvinceId($ids)
    {
        if (!is_array($ids)) {
            $ids = (array)$ids;
        }
        $this->provinceIdFilter = $ids;
    }

    public function getFilterOnProvinceId()
    {
        return $this->provinceIdFilter;
    }

    public function toArray()
    {
        $array = parent::toArray();

        if ($this->getFilterOnProvinceId()) {
            $array['provinces'] = $this->getFilterOnProvinceId();
        }

        return $array;
    }
}
