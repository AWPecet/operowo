<?php

namespace Operowo\Bundle\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Operowo\Bundle\UserBundle\Entity\User;

class LoadUsersData implements FixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < 10; $i++)
        {
            foreach(array('user', 'admin') as $type) {
                $user = new User();
                $user->setUsername(sprintf('%s_%s', $type, $i + 1));
                $user->setPlainPassword(sprintf('%s_%s', $type, $i + 1));
                $user->setEmail(sprintf('%s_%s@operowo.sf2', $type, $i + 1));
                $user->setEnabled(true);

                $manager->persist($user);
            }
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 5;
    }
}