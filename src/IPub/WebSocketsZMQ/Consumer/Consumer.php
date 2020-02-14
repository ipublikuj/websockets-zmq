<?php
/**
 * Consumer.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:WebSocketsZMQ!
 * @subpackage     Consumer
 * @since          1.0.0
 *
 * @date           28.02.17
 */

declare(strict_types = 1);

namespace IPub\WebSocketsZMQ\Consumer;

use Closure;
use ZMQ;
use ZMQSocketException;
use Throwable;

use Psr\Log;

use React;
use React\EventLoop;
use React\ZMQ as ReactZMQ;

use IPub;
use IPub\WebSocketsZMQ;

use IPub\WebSocketsWAMP\Application;
use IPub\WebSocketsWAMP\Entities;
use IPub\WebSocketsWAMP\PushMessages;
use IPub\WebSocketsWAMP\Serializers;

use IPub\WebSockets\Exceptions as WebSocketsExceptions;

/**
 * ZeroMQ consumer
 *
 * @package        iPublikuj:WebSocketsZMQ!
 * @subpackage     Consumer
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 *
 * @method onSuccess(Consumer $consumer, $data = NULL)
 * @method onFail(Consumer $consumer, $data = NULL)
 */
final class Consumer extends PushMessages\Consumer
{
	/**
	 * @var Closure
	 */
	public $onSuccess = [];

	/**
	 * @var Closure
	 */
	public $onFail = [];

	/**
	 * @var WebSocketsZMQ\Configuration
	 */
	private $configuration;

	/**
	 * @var  Serializers\PushMessageSerializer
	 */
	private $serializer;

	/**
	 * @var ReactZMQ\SocketWrapper
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
	 *
	 * @throws ZMQSocketException
	 */
	public function connect(EventLoop\LoopInterface $loop, Application\IApplication $application)
	{
		$context = new ReactZMQ\Context($loop);

		$this->socket = $context->getSocket(ZMQ::SOCKET_PULL);

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

				$application->handlePush($message, $this->getName());

				$this->onSuccess($this, $data);

			} catch (WebSocketsExceptions\TerminateException $ex) {
				throw $ex;

			} catch (Throwable $ex) {
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

		$this->socket->on('error', function (Throwable $ex) use ($application) {
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
	public function close() : void
	{
		$this->socket ?: $this->socket->close();
	}
}
