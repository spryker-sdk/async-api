<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\UtilText;

use Codeception\Test\Unit;
use Spryker\Service\UtilText\UtilTextService;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group UtilText
 * @group Facade
 * @group UtilTextFacadeTest
 * Add your own group annotations below this line
 */
class UtilTextFacadeTest extends Unit
{
    /**
     * @var \Spryker\Service\UtilText\UtilTextService
     */
    protected $utilTextFacade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->utilTextFacade = new UtilTextService();
    }

    /**
     * @return void
     */
    public function testGenerateSlug(): void
    {
        $slug = $this->utilTextFacade->generateSlug('A #value#, [to] Slug 8 times.');

        $expectedSlug = 'a-value-to-slug-8-times';

        $this->assertSame($expectedSlug, $slug);
    }

    /**
     * @return void
     */
    public function testGenerateRandomByteStringWillGenerateByteStringOfExpectedLength(): void
    {
        //Arrange
        $length = 64;

        //Act
        $string = $this->utilTextFacade->generateRandomByteString($length);

        //Assert
        $this->assertSame($length, strlen($string), 'String length did not match expected value.');
    }
}
