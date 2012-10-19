<?php

namespace Operowo\Bundle\MainBundle\Entity;

use Assert\Assertion;
use Doctrine\ORM\QueryBuilder;
use JMS\DiExtraBundle\Annotation\Service;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Service(id="operowo.institutions_criteria.transformer")
 */
class InstitutionsCriteriaToQueryBuilderTransformer
{
    /**
     */
    public function __construct()
    {
    }

    public function transform(InstitutionsCriteria $criteria, QueryBuilder $queryBuilder)
    {
        $this
            ->transformProvinces($criteria, $queryBuilder)
            ->applyPagination($criteria, $queryBuilder)
        ;

        return new Paginator($queryBuilder);
    }

    public function transformProvinces(InstitutionsCriteria $criteria, QueryBuilder $queryBuilder)
    {
        if (!$criteria->getFilterOnProvinceId()) {
            return $this;
        }

        $rootAliases = $queryBuilder->getRootAliases();
        $predicate = $queryBuilder->expr()->in(sprintf('%s.province', $rootAliases[0]), $criteria->getFilterOnProvinceId());
        $queryBuilder->andWhere($predicate);

        return $this;
    }

    public function applyPagination(InstitutionsCriteria $criteria, QueryBuilder $queryBuilder)
    {
        $queryBuilder->setFirstResult(abs($criteria->getCurrentPage() - 1) * $criteria->getMaxPerPage());
        $queryBuilder->setMaxResults($criteria->getMaxPerPage());

        return $this;
    }
}
