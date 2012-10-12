<?php

namespace Operowo\Bundle\MainBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Acme\HelloBundle\Entity\User;
use Operowo\Bundle\MainBundle\Entity\Institution;

class LoadInstitutionsData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
    	for($i = 0; $i < 30; $i++)
    	{
    		$institution = new Institution();
    		$institution->setName('Opera nr '.($i + 1));

        	$manager->persist($institution);
    	}

        $manager->flush();
    }
}