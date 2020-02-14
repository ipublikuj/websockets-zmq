<?php
/**
 * WebSocketsZMQExtension.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:WebSocketsZMQ!
 * @subpackage     DI
 * @since          1.0.0
 *
 * @date           01.03.17
 */

declare(strict_types = 1);

namespace IPub\WebSocketsZMQ\DI;

use Nette;
use Nette\DI;
use Nette\Schema;

use IPub\WebSocketsZMQ;
use IPub\WebSocketsZMQ\Consumer;
use IPub\WebSocketsZMQ\Pusher;

/**
 * WebSockets ZeroMQ extension container
 *
 * @package        iPublikuj:WebSocket!
 * @subpackage     DI
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
final class WebSocketsZMQExtension extends DI\CompilerExtension
{
	/**
	 * {@inheritdoc}
	 */
	public function getConfigSchema() : Schema\Schema
	{
		return Schema\Expect::structure([
			'host'       => Schema\Expect::string('127.0.0.1'),
			'port'       => Schema\Expect::int(5555),
			'persistent' => Schema\Expect::bool(TRUE),
			'protocol'   => Schema\Expect::string('tcp'),
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function loadConfiguration()
	{
		parent::loadConfiguration();

		$builder = $this->getContainerBuilder();
		$configuration = $this->getConfig();

		$zmqConfiguration = new WebSocketsZMQ\Configuration(
			$configuration->host,
			$configuration->port,
			$configuration->persistent,
			$configuration->protocol
		);

		$builder->addDefinition($this->prefix('consumer'))
			->setType(Consumer\Consumer::class)
			->setArguments(['configuration' => $zmqConfiguration]);

		$builder->addDefinition($this->prefix('pusher'))
			->setType(Pusher\Pusher::class)
			->setArguments(['configuration' => $zmqConfiguration]);
	}

	/**
	 * @param Nette\Configurator $config
	 * @param string $extensionName
	 *
	 * @return void
	 */
	public static function register(
		Nette\Configurator $config,
		string $extensionName = 'webSocketsZMQ'
	) : void {
		$config->onCompile[] = function (Nette\Configurator $config, DI\Compiler $compiler) use ($extensionName) {
			$compiler->addExtension($extensionName, new WebSocketsZMQExtension());
		};
	}
}
