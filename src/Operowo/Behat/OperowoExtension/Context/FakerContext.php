<?php

namespace Operowo\Behat\OperowoExtension\Context;

use Behat\Behat\Context\BehatContext;
use Behat\Behat\Event\ScenarioEvent;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class FakerContext extends BehatContext implements KernelAwareInterface
{
    private $kernel;

    /**
     * @Given /^Faker prepare "([^"]*)" fake "([^"]*)"$/
     */
    public function fakerPrepareFake($number, $entity)
    {
        switch($entity)
        {
            case 'institution':
                $entity = 'Operowo\Bundle\MainBundle\Entity\Institution';
                break;
        }
        $generator = \Faker\Factory::create('pl_PL');
        $populator = new \Faker\ORM\Doctrine\Populator($generator, $this->kernel->getContainer()->get('doctrine.orm.default_entity_manager'));
        $populator->addEntity($entity, $number);
        $populator->execute();
    }

    /**
     * Sets HttpKernel instance.
     * This method will be automatically called by Symfony2Extension ContextInitializer.
     *
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }
}