<?php
/**
 * Test: IPub\RatchetZMQ\Extension
 * @testCase
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:RatchetZMQ!
 * @subpackage     Tests
 * @since          1.0.0
 *
 * @date           01.03.17
 */

declare(strict_types = 1);

namespace IPubTests\RatchetZMQ;

use Nette;

use Tester;
use Tester\Assert;

use IPub;
use IPub\RatchetZMQ;

require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'bootstrap.php';

class ExtensionTest extends Tester\TestCase
{
	public function testCompilersServices()
	{
		$dic = $this->createContainer();

		Assert::true($dic->getService('ratchetZMQ.consumer') instanceof RatchetZMQ\Consumer\Consumer);
		Assert::true($dic->getService('ratchetZMQ.pusher') instanceof RatchetZMQ\Pusher\Pusher);
	}

	/**
	 * @return Nette\DI\Container
	 */
	protected function createContainer() : Nette\DI\Container
	{
		$config = new Nette\Configurator();
		$config->setTempDirectory(TEMP_DIR);

		$config->addConfig(__DIR__ . DS . 'files' . DS . 'config.neon');

		return $config->createContainer();
	}
}

\run(new ExtensionTest());
