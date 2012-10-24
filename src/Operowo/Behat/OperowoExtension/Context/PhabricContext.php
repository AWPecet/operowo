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
        $this->setUpUserEntity($phabricConfig);
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

        $this->phabric->createEntity('province', $phabricConfig['entities']['Province']);
    }

    protected function setUpUserEntity($phabricConfig)
    {
        $user = $this->phabric->createEntity('user', $phabricConfig['entities']['User']);
        $user->setNameTransformations(array(
            'Username canonical' => 'username_canonical',
            'Email canonical' => 'email_canonical',
            'Confirmation token' => 'confirmation_token'
        ));
        $user->setDefaults(array(
            'enabled' => 1,
            'locked' => 0,
            'expired' => 0,
            'roles' => serialize(array()),
            'credentials_expired' => 0
        ));
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

    /**
     * @Given /^The following users exists$/
     */
    public function theFollowingUsersExist(TableNode $users)
    {
        $encoder = new \Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder();
        $headersRow = $users->getRow(0);
        $newData = array();

        foreach ($users->getHash() as $key => $row) {
            if (isset($row['Username']) && !isset($row['Email'])) {
                $headersRow[] = 'Email';
                $row['Email'] = $row['Username'].'@operowo.sf2';
            }
            if (isset($row['Username']) && !isset($row['Salt'])) {
                $headersRow[] = 'Salt';
                $row['Salt'] = $row['Username'];
            }
            if (isset($row['Plain password']) && !isset($row['Password'])) {
                $headersRow[] = 'Password';
                $row['Password'] = $encoder->encodePassword($row['Plain password'], $row['Salt']);
            }
            if (!isset($row['Plain password']) && !isset($row['Password'])) {
                $headersRow[] = 'Password';
                $row['Password'] = $encoder->encodePassword($row['Username'], $row['Salt']);
            }
            if (isset($row['Username']) && !isset($headersRow['Username canonical'])) {
                $headersRow[] = 'Username canonical';
                $row['Username canonical'] = $row['Username'];
            }
            if (isset($row['Email']) && !isset($headersRow['Email canonical'])) {
                $headersRow[] = 'Email canonical';
                $row['Email canonical'] = $row['Email'];
            }

            $headersRow = array_unique($headersRow);

            $transformedRow = array();
            foreach ($row as $name => $value) {
                if ($name == 'Plain password') {
                    continue;
                }
                $transformedRow[array_search($name, $headersRow)] = $value;
            }

            $newData[$key+1] = $transformedRow;
        }

        $users->setRows(array($headersRow) + $newData);
        $this->phabric->updateFromTable('user', $users);
    }
}