<?php

namespace Operowo\Bundle\MainBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Operowo\Bundle\MainBundle\Entity\Province;

class LoadProvincesData implements FixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $provinces = array(
            'śląskie',
            'opolskie',
            'lubuskie',
            'małopolskie',
            'świętokrzyskie',
            'wielkopolskie',
            'podkarpackie',
            'pomorskie',
            'dolnośląskie',
            'zachodnio-pomorskie',
            'lubelskie',
            'łódzkie',
            'kujawsko-pomorskie',
            'warmińsko-mazurskie',
            'podlaskie',
            'mazowieckie'
        );
    	foreach ($provinces as $provinceName)
    	{
    		$province = new Province();
    		$province->setName($provinceName);

        	$manager->persist($province);
    	}

        $manager->flush();
    }

    public function getOrder()
    {
        return 10;
    }
}