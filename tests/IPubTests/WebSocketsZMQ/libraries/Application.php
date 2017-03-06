<?php
/**
 * Test: IPub\WebSocketsZMQ\Libraries
 * @testCase
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:WebSocketsZMQ!
 * @subpackage     Tests
 * @since          1.0.0
 *
 * @date           06.03.17
 */

declare(strict_types = 1);

namespace IPubTests\WebSocketsZMQ\Libraries;

use IPub\WebSockets;
use IPub\WebSockets\Entities;
use IPub\WebSockets\Http;

use IPub\WebSocketsWAMP\Application\V1\IApplication;
use IPub\WebSocketsWAMP\Entities\PushMessages;

class Application implements IApplication
{
	/**
	 * {@inheritdoc}
	 */
	function onOpen(Entities\Clients\IClient $client, Http\IRequest $httpRequest)
	{

	}

	/**
	 * {@inheritdoc}
	 */
	function onClose(Entities\Clients\IClient $client, Http\IRequest $httpRequest)
	{

	}

	/**
	 * {@inheritdoc}
	 */
	function onError(Entities\Clients\IClient $client, Http\IRequest $httpRequest, \Exception $ex)
	{

	}

	/**
	 * {@inheritdoc}
	 */
	function onMessage(Entities\Clients\IClient $from, Http\IRequest $httpRequest, string $message)
	{

	}

	/**
	 * {@inheritdoc}
	 */
	function onPush(PushMessages\IMessage $message, string $provider)
	{

	}

	/**
	 * {@inheritdoc}
	 */
	function getSubProtocols() : array
	{
		return [];
	}
}
