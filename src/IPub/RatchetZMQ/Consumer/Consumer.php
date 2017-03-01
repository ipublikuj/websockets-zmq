<?php
/**
 * Consumer.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:RatchetZMQ!
 * @subpackage     Consumer
 * @since          1.0.0
 *
 * @date           28.02.17
 */

declare(strict_types = 1);

namespace IPub\RatchetZMQ\Consumer;

use Psr\Log;

use React;
use React\EventLoop;
use React\ZMQ;

use IPub;
use IPub\RatchetZMQ;

use IPub\Ratchet\Application;
use IPub\Ratchet\Entities;
use IPub\Ratchet\PushMessages;
use IPub\Ratchet\Serializers;

/**
 * ZeroMQ consumer
 *
 * @package        iPublikuj:RatchetZMQ!
 * @subpackage     Consumer
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
final class Consumer extends PushMessages\Consumer
{
	/**
	 * @var RatchetZMQ\Configuration
	 */
	private $configuration;

	/**
	 * @var  Serializers\PushMessageSerializer
	 */
	private $serializer;

	/**
	 * @var ZMQ\SocketWrapper
	 */
	private $socket;

	/**
	 * @var  Log\LoggerInterface
	 */
	private $logger;

	/**
	 * @param RatchetZMQ\Configuration $configuration
	 * @param Serializers\PushMessageSerializer $serializer
	 * @param Log\LoggerInterface|NULL $logger
	 */
	public function __construct(
		RatchetZMQ\Configuration $configuration,
		Serializers\PushMessageSerializer $serializer,
		Log\LoggerInterface $logger = NULL
	) {
		$this->configuration = $configuration;
		$this->serializer = $serializer;
		$this->logger = $logger === NULL ? new Log\NullLogger : $logger;
	}

	/**
	 * {@inheritdoc}
	 */
	public function handle(EventLoop\LoopInterface $loop, Application\IApplication $application)
	{
		$context = new ZMQ\Context($loop);

		$this->socket = $context->getSocket(\ZMQ::SOCKET_PULL);

		$this->logger->info(sprintf(
			'ZMQ transport listening on %s:%s',
			$this->configuration->getHost(),
			$this->configuration->getPort()
		));

		$this->socket->bind($this->configuration->getProtocol() . '://' . $this->configuration->getHost() . ':' . $this->configuration->getPort());

		$this->socket->on('message', function ($data) use ($application) {
			try {
				/** @var Entities\PushMessages\IMessage $message */
				$message = $this->serializer->deserialize($data);

				$application->onPush($message, $this->getName());

				$this->onSuccess($data, $this);

			} catch (\Exception $ex) {
				$this->logger->error(
					'ZMQ socket failed to ack message', [
						'exception_message' => $ex->getMessage(),
						'file'              => $ex->getFile(),
						'line'              => $ex->getLine(),
						'message'           => $data,
					]
				);

				$this->onFail($data, $this);
			}
		});
	}

	/**
	 * {@inheritdoc}
	 */
	public function close()
	{
		$this->socket ?: $this->socket->close();
	}
}
