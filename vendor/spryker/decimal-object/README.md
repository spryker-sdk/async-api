#  Decimal Object

[![Build Status](https://github.com/spryker/decimal-object/workflows/CI/badge.svg?branch=master)](https://github.com/spryker/decimal-object/actions?query=workflow%3ACI+branch%3Amaster)
[![codecov](https://codecov.io/gh/spryker/decimal-object/branch/master/graph/badge.svg?token=L1thFB9nOG)](https://codecov.io/gh/spryker/decimal-object)
[![Latest Stable Version](https://poser.pugx.org/spryker/decimal-object/v/stable.svg)](https://packagist.org/packages/spryker/decimal-object)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.2-8892BF.svg)](https://php.net/)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%208-brightgreen.svg?style=flat)](https://phpstan.org/)
[![License](https://poser.pugx.org/spryker/decimal-object/license)](https://packagist.org/packages/spryker/decimal-object)

Decimal value object for PHP.

## Background
When working with monetary values, normal data types like int or float are not suitable for exact arithmetic.
Try out the following in PHP:
```php
var_dump(0.1 + 0.2);        // float(0.3)
var_dump(0.1 + 0.2 - 0.3);  // float(5.5511151231258E-17)
```

Handling them as string is a workaround, but as value object you can more easily encapsulate some of the logic.

### Alternatives
Solutions like https://php-decimal.io require a PHP extension (would make it faster, but also more difficult for some
servers to be available). For details see the [wiki](https://github.com/spryker/decimal-object/wiki).

## Features

- Super strict on precision/scale. Does not lose significant digits on its own. You need to `trim()` for this manually.
- Speaking API (no le, gt methods).
- Basic math operations and checks supported.
- Immutability.
- Handle very large and very small numbers.

## Installation

### Requirements

- `bcmath` PHP extension enabled

### Composer (preferred)
```
composer require spryker/decimal-object
```

## Usage

See [Documentation](/docs) for more details.

### Implementations
The following libraries are using the `Decimal` value object:

- [dereuromark/cakephp-decimal](https://github.com/dereuromark/cakephp-decimal) as decimal type replacement for CakePHP ORM.
