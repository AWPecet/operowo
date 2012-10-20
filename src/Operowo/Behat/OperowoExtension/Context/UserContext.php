<?php

namespace Operowo\Behat\OperowoExtension\Context;

use Behat\Behat\Context\BehatContext;
use Behat\Behat\Context\Step\Given;
use Behat\Behat\Context\Step\When;
use Behat\Behat\Context\Step\Then;
use Behat\Gherkin\Node\TableNode;

class UserContext extends BehatContext
{
    /**
     * @Given /^I am logged as "([^"]*)"$/
     */
    public function iAmLoggedAsUser($username)
    {
        return array(
            new Given('I am on "/login"'),
            new When(sprintf('I fill in "_username" with "%s"', $username)),
            new When(sprintf('I fill in "_password" with "%s"', $username)),
            new When('I press "Zaloguj"'),
            //new Then('print last response')
        );
    }
}