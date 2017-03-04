<?php
/**
 * Consumer.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:WebSocketZMQ!
 * @subpackage     Consumer
 * @since          1.0.0
 *
 * @date           28.02.17
 */

declare(strict_types = 1);

namespace IPub\WebSocketsZMQ\Consumer;

use Psr\Log;

use React;
use React\EventLoop;
use React\ZMQ;

use IPub;
use IPub\WebSocketsZMQ;

use IPub\WebSocketsWAMP\Application;
use IPub\WebSocketsWAMP\Entities;
use IPub\WebSocketsWAMP\PushMessages;
use IPub\WebSocketsWAMP\Serializers;

/**
 * ZeroMQ consumer
 *
 * @package        iPublikuj:WebSocketZMQ!
 * @subpackage     Consumer
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
final class Consumer extends PushMessages\Consumer
{
	/**
	 * @var WebSocketsZMQ\Configuration
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
	 * @param WebSocketsZMQ\Configuration $configuration
	 * @param Serializers\PushMessageSerializer $serializer
	 * @param Log\LoggerInterface|NULL $logger
	 */
	public function __construct(
		WebSocketsZMQ\Configuration $configuration,
		Serializers\PushMessageSerializer $serializer,
		Log\LoggerInterface $logger = NULL
	) {
		parent::__construct('zmq');

		$this->configuration = $configuration;
		$this->serializer = $serializer;
		$this->logger = $logger === NULL ? new Log\NullLogger : $logger;
	}

	/**
	 * {@inheritdoc}
	 */
	public function connect(EventLoop\LoopInterface $loop, Application\V1\IApplication $application)
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

				$this->onSuccess($this, $data);

			} catch (\Exception $ex) {
				$this->logger->error(
					'ZMQ socket failed to ack message', [
						'exception_message' => $ex->getMessage(),
						'file'              => $ex->getFile(),
						'line'              => $ex->getLine(),
						'message'           => $data,
					]
				);

				$this->onFail($this, $data);
			}
		});

		$this->socket->on('error', function (\Exception $ex) use ($application) {
			$this->logger->error(
				'ZMQ socket failed to receive message', [
					'exception_message' => $ex->getMessage(),
					'file'              => $ex->getFile(),
					'line'              => $ex->getLine(),
				]
			);

			$this->onFail($this);
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
