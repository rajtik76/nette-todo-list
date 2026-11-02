<?php declare(strict_types=1);

use Nette\Bootstrap\Configurator;
use Nette\DI\Container;

require __DIR__ . '/../vendor/autoload.php';

Tester\Environment::setup();
Tester\Environment::setupFunctions();


function createContainer(): Container
{
	$tempDir = __DIR__ . '/temp/' . getmypid();
	Nette\Utils\FileSystem::createDir($tempDir);

	$configurator = new Configurator;
	$configurator->setDebugMode(false);
	$configurator->setTempDirectory($tempDir);
	$configurator->addStaticParameters(['appDir' => __DIR__ . '/../app']);
	$configurator->addConfig(__DIR__ . '/../config/common.neon');
	$configurator->addConfig(__DIR__ . '/../config/services.neon');
	$configurator->addConfig(__DIR__ . '/config.neon');

	return $configurator->createContainer();
}
