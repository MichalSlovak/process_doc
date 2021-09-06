<?php

declare(strict_types=1);

namespace Sportisimo\ProcessDoc\Router;

use Nette;
use Nette\Application\Routers\RouteList;


final class RouterFactory
{
  /**
   * @return \Nette\Application\Routers\Route
   */
	public function createRouter(): Nette\Application\Routers\Route
	{
		$routerList = new RouteList();
        return new Nette\Application\Routers\Route('<presenter>/<action>[/<id>]', 'ApplicationList:default');
		//$routerList->add
		//return $routerList;
	} // createRouter()

} // RouterFactory
