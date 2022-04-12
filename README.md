# AsyncApi

[![Build Status](https://github.com/spryker-sdk/async-api/workflows/CI/badge.svg?branch=master)](https://github.com/spryker-sdk/async-api/actions?query=workflow%3ACI+branch%3Amaster)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.4-8892BF.svg)](https://php.net/)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%208-brightgreen.svg?style=flat)](https://phpstan.org/)

This library provides an AsyncAPI parser.

## Installation

- `composer require --dev spryker-sdk/async-api`

## Usage

### Parsing an AsyncAPI file

```php
use SprykerSdk\AsyncApi\Loader\AsyncApiLoader;

$asyncApiLoader = new AsyncApiLoader();
$asyncApi = $asyncApiLoader->load('.../path/to/async-api.yml');

// Get the channel(s)
$channels = $asyncApi->getChannels();
$channel = $asyncApi->getChannel('channel-name');

// Get publish message(s)
$publishMessages = $channel->getPublishMessages();
$publishMessage = $channel->getPublishMessage('message-name');

// Get subscribe message(s)
$subscribeMessages = $channel->getSubscribeMessages();
$subscribeMessage = $channel->getSubscribeMessage('message-name');

// Get message detail(s)
$messageAttributes = $subscribeMessage->getAttributes();
$messageAttribute = $subscribeMessage->getAttribute('attribute-name');
```


### Run tests/checks

- `composer test` - This will execute the tests.
- `composer cs-check` - This will run CodeStyle checks.
- `composer cs-fix` - This will fix fixable CodeStyles.
- `composer stan` - This will run PHPStan checks.
