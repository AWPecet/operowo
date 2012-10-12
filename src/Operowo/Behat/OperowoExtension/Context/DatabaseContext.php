<?php

namespace Operowo\Behat\OperowoExtension\Context;

use Behat\Behat\Context\BehatContext;
use Behat\Behat\Event\ScenarioEvent;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;

class DatabaseContext extends BehatContext implements KernelAwareInterface
{
    private $kernel;

	/**
	 * @BeforeScenario @database
	 */
	public function createResetSchema(ScenarioEvent $event)
	{
		$appDir = $this->kernel->getRootDir();
		self::executeCommand($appDir, 'doctrine:schema:drop --force --env=test');
		self::executeCommand($appDir, 'doctrine:schema:create --env=test');
		
		return true;
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

    protected static function executeCommand($appDir, $cmd, $timeout = 300)
    {
        $php = escapeshellarg(self::getPhp());
        //$php = 'php';
        $console = escapeshellarg($appDir.'/console');
        /*if ($event->getIO()->isDecorated()) {
            $console.= ' --ansi';
        }*/

        $process = new Process($php.' '.$console.' '.$cmd, null, null, null, $timeout);
        $process->run(function ($type, $buffer) { $buffer; });
        if (!$process->isSuccessful()) {
            throw new \RuntimeException(sprintf('An error occurred when executing the "%s" command.', escapeshellarg($cmd)));
        }
    }

    protected static function getPhp()
    {
        $phpFinder = new PhpExecutableFinder;
        if (!$phpPath = $phpFinder->find()) {
            throw new \RuntimeException('The php executable could not be found, add it to your PATH environment variable and try again');
        }

        return $phpPath;
    }
}