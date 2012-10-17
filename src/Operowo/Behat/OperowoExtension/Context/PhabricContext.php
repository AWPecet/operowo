<?php

namespace Operowo\Behat\OperowoExtension\Context;

use Behat\Behat\Context\BehatContext;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Operowo\Phabric\Datasource\Doctrine;
use Phabric\Phabric;
use Symfony\Component\Yaml\Yaml;
use Behat\Gherkin\Node\TableNode;

class PhabricContext extends BehatContext
{
    protected $phabric;

    public function __construct(array $parameters)
    {
        $config = new Configuration();

        $externalParameters = Yaml::parse(file_get_contents($parameters['db_parameters_file']));

        $db = DriverManager::getConnection(array(
            'dbname' => $externalParameters['parameters']['database_test_name'],
            'user' => $externalParameters['parameters']['database_test_user'],
            'password' => $externalParameters['parameters']['database_test_password'],
            'host' => $externalParameters['parameters']['database_test_host'],
            'driver' => $externalParameters['parameters']['database_test_driver'],
        ));

        $datasource = new Doctrine($db, $parameters['entities']);

        $this->phabric = new Phabric($datasource);

        $this->setUpEntities($parameters);
    }

    protected function setUpEntities($phabricConfig)
    {
        $this->setUpInstitutionEntity($phabricConfig);
        $this->setUpProvinceEntity($phabricConfig);
    }

    protected function setUpInstitutionEntity($phabricConfig)
    {
        $institution = $this->phabric->createEntity('institution', $phabricConfig['entities']['Institution']);

        $institution->setNameTransformations(array(
            'Province' => 'province_id'
        ));

        $institution->setDataTransformations(array(
            'province_id' => 'PROVINCELOOKUP'
        ));
    }

    protected function setUpProvinceEntity($phabricConfig)
    {
        $this->phabric->addDataTransformation(
            'PROVINCELOOKUP', function($provinceName, $bus) {
            $ent = $bus->getEntity('province');

            $id = $ent->getNamedItemId($provinceName);

            return $id;
        });

        $province = $this->phabric->createEntity('province', $phabricConfig['entities']['Province']);
    }

    /**
     * @Given /^The following institutions exists$/
     */
    public function theFollowingInstitutionsExist(TableNode $institutions)
    {
        $this->phabric->updateFromTable('institution', $institutions);
    }

    /**
     * @Given /^The following provinces exists$/
     */
    public function theFollowingProvincesExist(TableNode $provinces)
    {
        $this->phabric->updateFromTable('province', $provinces);
    }
}