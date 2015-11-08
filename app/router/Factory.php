<?php

namespace gplusFlickr\router;

use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;

/**
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class Factory {
	/**
	 * @return \Nette\Application\IRouter
	 */
	public static function createRouter() {
		$router = new RouteList();
		$router[] = new Route('<presenter>/<action>[/<id>]', 'Homepage:default');
		return $router;
	}
}
