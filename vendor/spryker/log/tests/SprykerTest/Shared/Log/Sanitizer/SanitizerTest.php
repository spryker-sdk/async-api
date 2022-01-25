<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Log\Sanitizer;

use Codeception\Test\Unit;
use Spryker\Shared\Log\Sanitizer\Sanitizer;
use Spryker\Shared\Log\Sanitizer\SanitizerInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Log
 * @group Sanitizer
 * @group SanitizerTest
 * Add your own group annotations below this line
 */
class SanitizerTest extends Unit
{
    /**
     * @var string
     */
    public const SANITIZED_VALUE = '***';

    /**
     * @return void
     */
    public function testInstantiateWithArguments(): void
    {
        $sanitizer = new Sanitizer([], static::SANITIZED_VALUE);

        $this->assertInstanceOf(SanitizerInterface::class, $sanitizer);
    }

    /**
     * @return void
     */
    public function testSanitizeValueValueShouldNotSanitizeWhenKeysNotMatching(): void
    {
        $sanitizer = new Sanitizer(['foo'], static::SANITIZED_VALUE);

        $this->assertSame('bar', $sanitizer->sanitizeValue('bar', 'baz'));
    }

    /**
     * @return void
     */
    public function testSanitizeValueShouldReturnSanitizedWhenKeyMatches(): void
    {
        $sanitizer = new Sanitizer(['sanitize'], static::SANITIZED_VALUE);

        $this->assertSame(static::SANITIZED_VALUE, $sanitizer->sanitizeValue('bar', 'sanitize'));
    }

    /**
     * @return void
     */
    public function testSanitizeShouldReturnNotSanitizedWhenKeysNotMatching(): void
    {
        $sanitizer = new Sanitizer(['sanitize'], static::SANITIZED_VALUE);

        $input = ['foo' => 'bar'];
        $expected = $input;

        $this->assertSame($expected, $sanitizer->sanitize($input));
    }

    /**
     * @return void
     */
    public function testSanitizeShouldReturnSanitizedWhenKeyMatches(): void
    {
        $sanitizer = new Sanitizer(['sanitize'], static::SANITIZED_VALUE);

        $input = ['sanitize' => 'sanitize me'];
        $expected = ['sanitize' => static::SANITIZED_VALUE];

        $this->assertSame($expected, $sanitizer->sanitize($input));
    }

    /**
     * @return void
     */
    public function testSanitizeWithInnerArrayShouldReturnSanitizedWhenKeyMatches(): void
    {
        $sanitizer = new Sanitizer(['sanitize', 'password'], static::SANITIZED_VALUE);

        $input = [
            'foo' => 'bar',
            'bar' => [
                'sanitize' => 'sanitize me',
            ],
            'password' => [
                'pass' => 'my secret password',
                'confirm' => 'my secret password',
            ],
        ];
        $expected = $input;
        $expected['bar']['sanitize'] = static::SANITIZED_VALUE;
        $expected['password'] = static::SANITIZED_VALUE;

        $this->assertSame($expected, $sanitizer->sanitize($input));
    }

    /**
     * @return void
     */
    public function testSanitizeWithIndexedArrayShouldReturnSanitizedWhenKeyMatches(): void
    {
        $sanitizer = new Sanitizer(['sanitize'], static::SANITIZED_VALUE);

        $input = [
            'foo' => 'bar',
            [
                ['sanitize' => 'sanitize me'],
                ['baz' => 'bat'],
            ],
        ];
        $expected = $input;
        $expected[0][0]['sanitize'] = static::SANITIZED_VALUE;

        $this->assertSame($expected, $sanitizer->sanitize($input));
    }
}
