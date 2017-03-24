<?php
/**
 * Configuration.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:WebSocketsZMQ!
 * @subpackage     common
 * @since          1.0.0
 *
 * @date           28.02.17
 */

declare(strict_types = 1);

namespace IPub\WebSocketsZMQ;

use Nette;

/**
 * ZeroMQ Pusher & consumer configuration container
 *
 * @package        iPublikuj:WebSocketsZMQ!
 * @subpackage     common
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
final class Configuration
{
	/**
	 * Implement nette smart magic
	 */
	use Nette\SmartObject;

	/**
	 * @var string
	 */
	private $host;

	/**
	 * @var int
	 */
	private $port;

	/**
	 * @var string
	 */
	private $persistent;

	/**
	 * @var string
	 */
	private $protocol;

	/**
	 * @param string $host
	 * @param int $port
	 * @param bool $persistent
	 * @param string $protocol
	 */
	public function __construct(
		string $host = '127.0.0.1',
		int $port = 5555,
		bool $persistent = TRUE,
		string $protocol = 'tcp'
	) {
		$this->host = $host;
		$this->port = $port;
		$this->persistent = $persistent;
		$this->protocol = $protocol;
	}

	/**
	 * @return string
	 */
	public function getHost() : string
	{
		return $this->host;
	}

	/**
	 * @return int
	 */
	public function getPort() : int
	{
		return $this->port;
	}

	/**
	 * @return bool
	 */
	public function isPersistent() : bool
	{
		return $this->persistent;
	}

	/**
	 * @return string
	 */
	public function getProtocol() : string
	{
		return $this->protocol;
	}
}
