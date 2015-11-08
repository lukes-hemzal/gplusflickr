<?php

namespace gplusFlickr;

use Nette\Configurator,
	Tracy\Debugger;

/**
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class App {
	/** @var string */
	private static $root;
	/** @var Configurator */
	private static $configurator;

	/**
	 * @param string $root
	 */
	public static function init($root) {
		self::$root = $root;
		require self::getDir('vendor') . '/autoload.php';
	}

	/**
	 * @return \Nette\DI\Container
	 */
	public static function getContainer() {
		if (!self::$configurator) {
			self::$configurator = self::prepareConfigurator();
		}
		return self::$configurator->createContainer();
	}

	/**
	 * @param string $type
	 * @return string
	 */
	public static function getDir($type) {
		return self::$root . $type;
	}

	/**
	 * @return Configurator
	 */
	private static function prepareConfigurator() {
		$configurator = new Configurator();
		$configurator->enableDebugger(self::getDir('log'));
		$configurator->setTempDirectory(self::getDir('temp'));
		$configurator->createRobotLoader()
			->addDirectory(self::getDir('app'))
			->register();
		$configDir = self::getDir('config');
		$configurator->addConfig("{$configDir}/config.neon");
		$configurator->addConfig("{$configDir}/config.local.neon");

		Debugger::$maxDepth = 5;
		return $configurator;
	}
}
