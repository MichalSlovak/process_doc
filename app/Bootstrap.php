<?php

declare(strict_types=1);

namespace Sportisimo\ProcessDoc;

use Nette\Configurator;
use Tracy\Debugger;

class Bootstrap
{
	public static function boot(): Configurator
	{
		$configurator = new Configurator;

//		if (getenv('ENVIRONMENT') === 'debug') {
//			$configurator->setDebugMode(true); // enable for your remote IP
//		} else {
//			$configurator->setDebugMode(false); // disable
//		}

    $configurator->setDebugMode(true);


		$configurator->enableTracy(__DIR__ . '/../log');
		$configurator->setTimeZone('Europe/Prague');
		$configurator->setTempDirectory(__DIR__ . '/../temp');
		$configurator->addConfig(__DIR__ . '/Config/config.neon');
		$configurator->addConfig(__DIR__ . '/Config/config.local.neon');

		Debugger::$maxDepth=10;

		return $configurator;
	}
}
