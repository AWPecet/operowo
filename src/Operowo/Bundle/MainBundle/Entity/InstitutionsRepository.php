<?php

namespace Operowo\Bundle\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\DiExtraBundle\Annotation\Service;
use Doctrine\ORM\EntityRepository;
use JMS\DiExtraBundle\Annotation as DI;

class InstitutionsRepository extends EntityRepository
{
	public function findByCriteria(InstitutionsCriteria $criteria, &$count = null)
	{
		$qb = $this->createQueryBuilder('i');

        $criteriaTransformer = new InstitutionsCriteriaToQueryBuilderTransformer();
        $paginator = $criteriaTransformer->transform($criteria, $qb);
        $count = $paginator->count();

        return $paginator->getIterator();
	}

    public function getDistributionInProvinces($withZero = false)
    {
        $query = 'SELECT p AS province, COUNT(i.id) AS distribution FROM Operowo\Bundle\MainBundle\Entity\Province p LEFT JOIN p.institutions i GROUP BY p.id %s ORDER BY p.name';
        if (!$withZero) {
            $query = sprintf($query, 'HAVING COUNT(i.id) > 0');
        } else {
            $query = sprintf($query, '');
        }
        $query = $this->_em->createQuery($query);
        $results = $query->getResult();
        foreach ($results as $key => $result) {
            if (!$withZero && $result['distribution'] == 0) {
                unset($results[$key]);
            }
        }

        return $results;
    }
}