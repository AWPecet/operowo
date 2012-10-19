<?php

namespace Operowo\Bundle\MainBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Operowo\Bundle\MainBundle\Entity\Institution;

class LoadInstitutionsData implements FixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $provinces = $manager->getRepository('Operowo\Bundle\MainBundle\Entity\Province')->findAll();

    	for($i = 0; $i < 30; $i++)
    	{
    		$institution = new Institution();
    		$institution->setName('Opera nr '.($i + 1));
            $randomKey = array_rand($provinces);
            $institution->setProvince($provinces[$randomKey]);

        	$manager->persist($institution);
    	}

        $manager->flush();
    }

    public function getOrder()
    {
        return 20;
    }
}