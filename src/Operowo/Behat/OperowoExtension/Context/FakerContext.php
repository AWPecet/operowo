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
     * @Given /^Faker prepare "([^"]*)" fake of institution$/
     */
    public function fakerPrepareFake($number)
    {
        $institutionEntity = 'Operowo\Bundle\MainBundle\Entity\Institution';
        $provinceEntity = 'Operowo\Bundle\MainBundle\Entity\Province';
        $entityManager =  $this->kernel->getContainer()->get('doctrine.orm.default_entity_manager');

        $generator = \Faker\Factory::create('pl_PL');
        $populator = new \Faker\ORM\Doctrine\Populator($generator, $entityManager);
        $populator->addEntity($provinceEntity, 1);
        $populator->addEntity($institutionEntity, $number, array(
            'province' => function($insertedEntities) use($entityManager, $provinceEntity) { return $insertedEntities[$provinceEntity][0]; }
        ));
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