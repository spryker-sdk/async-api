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
use SprykerSdk\AsyncApi\AsyncApi\Loader\AsyncApiLoader;

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

Documentation

The following console commands are available:

- `vendor/bin/asyncapi schema:create`
- `vendor/bin/asyncapi schema:add:message`
- `vendor/bin/asyncapi schema:validate`
- `vendor/bin/asyncapi code:generate`

## Adding an AsyncAPI file

The `vendor/bin/asyncapi schema:create` adds a minimal AsyncAPI file.

### Arguments and Options

#### Arguments

- `title`

`vendor/bin/asyncapi schema:create "Your Async API title"` will set the title in your AsyncAPI file.

```
...
info:
    title: 'Your Async API title'
...
```

#### Options

- `asyncapi-file`
- `api-version`

`vendor/bin/asyncapi schema:create --asyncapi-file "path/to/async-api.yml"` will override the default file location (config/api/asyncapi/asyncapi.yml).

`vendor/bin/asyncapi schema:create --api-version 1.0.0` will override the default file version (0.1.0).

## Adding a message to an AsyncAPI file

The `vendor/bin/asyncapi schema:add:message` adds a message to a given AsyncAPI file. This command can also be used to reverse engineer from an existing Transfer object.

This console command has many options to be configured. See all of them by running

`vendor/bin/asyncapi schema:add:message -h`

it will print a help page for this command.


## Validating an AsyncAPI file

The `vendor/bin/asyncapi schema:validate` validates a given AsyncAPI file.


## Create code from an existing AsyncAPI

The `vendor/bin/asyncapi code:generate` reads an existing AsyncAPI file and creates code out of it. This command creates:

- Message Transfer definitions (XML)
- Adds handler for Messages that are sent to the application

#### Options

- `asyncapi-file`, can be used to run the generator with a specific AsyncAPI file
- `organization`, can be used to set a specific organization, when set to Spryker code will be generated in the core modules (default: App)
