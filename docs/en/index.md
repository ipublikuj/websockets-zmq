# Quickstart

This extension is implementing [ZeroMQ](http://zeromq.org/) pusher & consumer into [ipub/websockets-wamo](https://github.com/iPublikuj/websockets-wamp)

## Installation

The best way to install ipub/websockets-zmq is using  [Composer](http://getcomposer.org/):

```sh
$ composer require ipub/websockets-zmq
```

After that you have to register extension in config.neon.

```neon
extensions:
	webSocketsZMQ: IPub\WebSocketsZMQ\DI\WebSocketsZMQExtension
```

## Usage

This extension require installed **php-zmq** extension. 

### 1. PHP extension installation 

```bash
sudo pecl install zmq-beta
```

```bash
touch /etc/php5/mods-available/zmq.ini
echo 'extension=zmq.so' >> /etc/php5/mods-available/zmq.ini
sudo php5enmod zmq
```

Then reload php-fpm server or apache/nginx if you are not using php-fpm

### Configuration

```neon
    webSocketsZMQ:
        host: '127.0.0.1'
        port: 5555
        persistent: true
        protocol: 'tcp'
```

* **Host** is address where event loop for websockets is running
* **Port** is port number for listeners
* **Protocol** is protocol type used in communication between pusher & consumer

After that, you will see this message when you start the websocket server

```sh
 ! [NOTE] ZMQ transport listening on 127.0.0.1:5555
```

### Pushing message

For pushing messages is defined pusher service. Just import this service to your presenter, model, etc. and push message to specific topic.

```php
public function YourCoolPresenter extends BasePresenter
{
    /**
     * IPub\WebSocketsZMQ\Pusher\Pusher
     */
    private $zmqPusher;

    public function __construct(IPub\WebSocketsZMQ\Pusher\Pusher $zmqPusher)
    {
        $this->zmqPusher = $zmqPusher;
    }
    
    public function handlePushMessage($data)
    {
        // push(data, route_name, route_arguments)
        $this->zmqPusher->push([$data], 'topic/name', ['routeParam' => 'routeValue']);
    }
}
```

### Consume received message

Once message is received through connection, application will call **Push** action of your controller:
 
```php
class YourWebSocketsController extends IPub\WebSockets\Application\Controller\Controller
{
    public function actionPush($data, IPub\WebSocketsWAMP\Entities\Topics\ITopic $topic)
    {
        // do what you want, eg. send message to all subscribers
        $topic->broadcast($data);
    }
}
```

## Consumer events

When consumer fail to process pushed message or when it push to the controller, extension dispatch an event to plug you own logic.

```php
IPub\WebSocketsZMQ\Consumer\Consumer::onSuccess(IPub\WebSocketsZMQ\Consumer\Consumer $consumer, $data = NULL)
IPub\WebSocketsZMQ\Consumer\Consumer::onFail(IPub\WebSocketsZMQ\Consumer\Consumer $consumer, $data = NULL)
```
