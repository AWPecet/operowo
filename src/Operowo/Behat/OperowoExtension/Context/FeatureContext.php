<?php

namespace Operowo\Behat\OperowoExtension\Context;

use Behat\MinkExtension\Context\MinkContext;

class FeatureContext extends MinkContext
{
	public function __construct(array $parameters)
	{
		$this->useContext('database_context', new DatabaseContext());
		$this->useContext('faker_context', new FakerContext());
	}
}