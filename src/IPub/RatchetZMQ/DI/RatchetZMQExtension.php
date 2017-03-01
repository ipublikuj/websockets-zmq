<?php
/**
 * RatchetZMQExtension.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:RatchetZMQ!
 * @subpackage     DI
 * @since          1.0.0
 *
 * @date           01.03.17
 */

declare(strict_types = 1);

namespace IPub\Ratchet\DI;

use Nette;
use Nette\DI;

use IPub;
use IPub\RatchetZMQ;
use IPub\RatchetZMQ\Consumer;
use IPub\RatchetZMQ\Pusher;

/**
 * Ratchet ZeroMQ                  extension container
 *
 * @package        iPublikuj:Ratchet!
 * @subpackage     DI
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 *
 * @method DI\ContainerBuilder getContainerBuilder()
 * @method array getConfig(array $defaults)
 * @method string prefix(string $name)
 */
final class RatchetZMQExtension extends DI\CompilerExtension
{
	/**
	 * @var array
	 */
	private $defaults = [
		'host'       => '127.0.0.1',
		'port'       => 5555,
		'persistent' => TRUE,
		'protocol'   => 'tcp',
	];

	/**
	 * {@inheritdoc}
	 */
	public function loadConfiguration()
	{
		parent::loadConfiguration();

		$builder = $this->getContainerBuilder();
		// Get extension configuration
		$configuration = $this->getConfig($this->defaults);

		$configuration = new RatchetZMQ\Configuration(
			$configuration['host'],
			$configuration['port'],
			$configuration['persistent'],
			$configuration['protocol']
		);

		$builder->addDefinition($this->prefix('consumer'))
			->setClass(Consumer\Consumer::class)
			->setArguments(['configuration' => $configuration]);

		$builder->addDefinition($this->prefix('pusher'))
			->setClass(Pusher\Pusher::class)
			->setArguments(['configuration' => $configuration]);
	}

	/**
	 * @param Nette\Configurator $config
	 * @param string $extensionName
	 *
	 * @return void
	 */
	public static function register(Nette\Configurator $config, string $extensionName = 'ratchetZMQ')
	{
		$config->onCompile[] = function (Nette\Configurator $config, DI\Compiler $compiler) use ($extensionName) {
			$compiler->addExtension($extensionName, new RatchetZMQExtension());
		};
	}
}
