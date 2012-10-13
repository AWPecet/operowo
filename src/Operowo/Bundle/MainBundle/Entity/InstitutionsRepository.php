<?php

namespace Operowo\Bundle\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\DiExtraBundle\Annotation\Service;
use Doctrine\ORM\EntityRepository;
use JMS\DiExtraBundle\Annotation as DI;

class InstitutionsRepository extends EntityRepository
{

	public function getQuery()
	{
		return $this->createQueryBuilder('i')->getQuery();
	}
}