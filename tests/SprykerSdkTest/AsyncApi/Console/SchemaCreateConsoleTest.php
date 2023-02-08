<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\AsyncApi\Console;

use Codeception\Test\Unit;
use SprykerSdk\AsyncApi\Console\AbstractConsole;
use SprykerSdk\AsyncApi\Console\SchemaCreateConsole;
use SprykerSdkTest\AsyncApi\AsyncApiTester;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @group SprykerSdkTest
 * @group AsyncApi
 * @group Console
 * @group SchemaCreateConsoleTest
 */
class SchemaCreateConsoleTest extends Unit
{
    /**
     * @var \SprykerSdkTest\AsyncApi\AsyncApiTester
     */
    protected AsyncApiTester $tester;

    /**
     * @return void
     */
    public function testAsyncApiCreateConsole(): void
    {
        $schemaCreateConsole = new SchemaCreateConsole(null, $this->tester->getConfig());

        $commandTester = $this->tester->getConsoleTester($schemaCreateConsole);

        // Act
        $commandTester->execute([SchemaCreateConsole::ARGUMENT_TITLE => 'Test File'], ['verbosity' => OutputInterface::VERBOSITY_VERBOSE]);

        // Assert
        $this->assertSame(AbstractConsole::CODE_SUCCESS, $commandTester->getStatusCode());
    }
}
