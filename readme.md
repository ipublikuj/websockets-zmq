# Ratchet ZeroMQ

[![Build Status](https://img.shields.io/travis/iPublikuj/ratchet-zmq.svg?style=flat-square)](https://travis-ci.org/iPublikuj/ratchet-zmq)
[![Scrutinizer Code Coverage](https://img.shields.io/scrutinizer/coverage/g/iPublikuj/ratchet-zmq.svg?style=flat-square)](https://scrutinizer-ci.com/g/iPublikuj/ratchet-zmq/?branch=master)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/iPublikuj/ratchet-zmq.svg?style=flat-square)](https://scrutinizer-ci.com/g/iPublikuj/ratchet-zmq/?branch=master)
[![Latest Stable Version](https://img.shields.io/packagist/v/ipub/ratchet-zmq.svg?style=flat-square)](https://packagist.org/packages/ipub/ratchet-zmq)
[![Composer Downloads](https://img.shields.io/packagist/dt/ipub/ratchet-zmq.svg?style=flat-square)](https://packagist.org/packages/ipub/ratchet-zmq)
[![License](https://img.shields.io/packagist/l/ipub/ratchet-zmq.svg?style=flat-square)](https://packagist.org/packages/ipub/ratchet-zmq)

Extension for implementing [Ratchet](http://socketo.me/) WebSockets into [Nette Framework](http://nette.org/)

## Installation

The best way to install ipub/ratchet-zmq is using  [Composer](http://getcomposer.org/):

```sh
$ composer require ipub/ratchet-zmq
```

After that you have to register extension in config.neon.

```neon
extensions:
	ratchetZMQ: IPub\RatchetZMQ\DI\RatchetZMQExtension
```

## Documentation

Learn how to create WebSocket server & controllers in [documentation](https://github.com/iPublikuj/ratchet-zmq/blob/master/docs/en/index.md).

***
Homepage [http://www.ipublikuj.eu](http://www.ipublikuj.eu) and repository [http://github.com/iPublikuj/ratchet-zmq](http://github.com/iPublikuj/ratchet-zmq).
