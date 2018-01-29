<?php

namespace App;

use Nette;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;


class RouterFactory
{
	use Nette\StaticClass;

	/**
	 * @return Nette\Application\IRouter
	 */
	public static function createRouter()
	{
		$router = new RouteList;
		$router[] = new Route('/', 'Dashboard:home');
		$router[] = new Route('/login', 'Authentication:login');
		$router[] = new Route('/logout', 'Authentication:logout');
		return $router;
	}
}
