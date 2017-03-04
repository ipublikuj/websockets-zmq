# WebSockets ZeroMQ

[![Build Status](https://img.shields.io/travis/iPublikuj/websockets-zmq.svg?style=flat-square)](https://travis-ci.org/iPublikuj/websockets-zmq)
[![Scrutinizer Code Coverage](https://img.shields.io/scrutinizer/coverage/g/iPublikuj/websockets-zmq.svg?style=flat-square)](https://scrutinizer-ci.com/g/iPublikuj/websockets-zmq/?branch=master)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/iPublikuj/websockets-zmq.svg?style=flat-square)](https://scrutinizer-ci.com/g/iPublikuj/websockets-zmq/?branch=master)
[![Latest Stable Version](https://img.shields.io/packagist/v/ipub/websockets-zmq.svg?style=flat-square)](https://packagist.org/packages/ipub/websockets-zmq)
[![Composer Downloads](https://img.shields.io/packagist/dt/ipub/websockets-zmq.svg?style=flat-square)](https://packagist.org/packages/ipub/websockets-zmq)
[![License](https://img.shields.io/packagist/l/ipub/websockets-zmq.svg?style=flat-square)](https://packagist.org/packages/ipub/websockets-zmq)

Extension for implementing [WebSockets](http://socketo.me/) WebSockets into [Nette Framework](http://nette.org/)

## Installation

The best way to install ipub/websockets-zmq is using  [Composer](http://getcomposer.org/):

```sh
$ composer require ipub/websockets-zmq
```

After that you have to register extension in config.neon.

```neon
extensions:
	websocketsZMQ: IPub\WebSocketsZMQ\DI\WebSocketsZMQExtension
```

## Documentation

Learn how to create WebSocket server & controllers in [documentation](https://github.com/iPublikuj/websockets-zmq/blob/master/docs/en/index.md).

***
Homepage [http://www.ipublikuj.eu](http://www.ipublikuj.eu) and repository [http://github.com/iPublikuj/websockets-zmq](http://github.com/iPublikuj/websockets-zmq).
