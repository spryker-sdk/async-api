# AsyncApi

[![Build Status](https://github.com/spryker-sdk/async-api/workflows/CI/badge.svg?branch=master)](https://github.com/spryker-sdk/async-api/actions?query=workflow%3ACI+branch%3Amaster)
[![Latest Stable Version](https://poser.pugx.org/spryker-sdk/async-api/v/stable.svg)](https://packagist.org/packages/spryker-sdk/async-api)
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

- `vendor/bin/asyncapi schema:asyncapi:create`
- `vendor/bin/asyncapi schema:asyncapi:message:add`
- `vendor/bin/asyncapi schema:asyncapi:validate`
- `vendor/bin/asyncapi code:asyncapi:generate`

## Adding an AsyncAPI file

The `vendor/bin/asyncapi schema:asyncapi:create` adds a minimal AsyncAPI file.

### Arguments and Options

#### Arguments

- `title`

`vendor/bin/asyncapi schema:asyncapi:create "Your Async API title"` will set the title in your AsyncAPI file.

```
...
info:
    title: 'Your Async API title'
...
```

#### Options

- `asyncapi-file`
- `api-version`

`vendor/bin/asyncapi schema:asyncapi:create --asyncapi-file "path/to/async-api.yml"` will override the default file location (resources/api/asyncapi.yml).

`vendor/bin/asyncapi schema:asyncapi:create --api-version 1.0.0` will override the default file version (0.1.0).

## Adding a message to an AsyncAPI file

The `vendor/bin/asyncapi schema:asyncapi:message:add` adds a message to a given AsyncAPI file. This command can also be used to reverse engineer from an existing Transfer object.

This console command has many options to be configured. See all of them by running

`vendor/bin/asyncapi schema:asyncapi:message:add -h`

it will print a help page for this command.

### Adding a subscribe message

To subscribe to messages from a specific channel you need to run the command as following:

`vendor/bin/asyncapi schema:asyncapi:message:add foo-bar ZipZap ModuleName -e subscribe -P propertyA:string -P propertyB:int`

This will add a subscribe section to the given AsyncAPI schema file that describes that sent messages with the name "ZipZap" that are sent over the channel "foo-bar" and that you sent the properties "propertyA of type string" and "propertyB of type int".

You can now create code from this definition.

### Adding a publish message

To receive messages from a specific channel you need to run the command as following:

`vendor/bin/asyncapi schema:asyncapi:message:add foo-bar ZipZap ModuleName -e publish -P propertyA:string -P propertyB:int`

This will add a publish section to the given AsyncAPI schema file that describes that you want to receive messages with the name "ZipZap" that are sent over the channel "foo-bar" and that you want to use the properties "propertyA of type string" and "propertyB of type int".

You can now create code from this definition.

### Reverse Engineer from given Transfer


## Validating an AsyncAPI file

The `vendor/bin/asyncapi schema:asyncapi:validate` validates a given AsyncAPI file.


## Create code from an existing AsyncAPI

The `vendor/bin/asyncapi code:asyncapi:generate` reads an existing AsyncAPI file and creates code out of it. This command creates:

- Message Transfer definitions (XML)
- Adds handler for Messages that are sent to the application

#### Options

- `asyncapi-file`, can be used to run the generator with a specific AsyncAPI file
- `organization`, can be used to set a specific organization, when set to Spryker code will be generated in the core modules (default: App)
