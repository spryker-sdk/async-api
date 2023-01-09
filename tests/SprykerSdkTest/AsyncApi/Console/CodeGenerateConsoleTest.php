<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\AsyncApi\Console;

use Codeception\Test\Unit;
use SprykerSdk\AsyncApi\Console\AbstractConsole;
use SprykerSdk\AsyncApi\Console\CodeGenerateConsole;
use SprykerSdk\AsyncApi\Message\AsyncApiError;
use SprykerSdk\AsyncApi\Message\AsyncApiInfo;
use SprykerSdkTest\AsyncApi\AsyncApiTester;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @group SprykerSdkTest
 * @group AsyncApi
 * @group Console
 * @group CodeGenerateConsoleTest
 */
class CodeGenerateConsoleTest extends Unit
{
    /**
     * @var \SprykerSdkTest\AsyncApi\AsyncApiTester
     */
    protected AsyncApiTester $tester;

    /**
     * @return void
     */
    public function testBuildFromAsyncApiReturnsSuccessCodeWhenProcessIsDone(): void
    {
        // Arrange
        $buildFromAsyncApiConsoleMock = $this->tester->getAsyncApiBuilderConsoleMock();

        $commandTester = $this->tester->getConsoleTester($buildFromAsyncApiConsoleMock);

        // Act
        $commandTester->execute([
            '--' . CodeGenerateConsole::OPTION_ASYNC_API_FILE => codecept_data_dir('api/builder/asyncapi.yml'),
            '--' . CodeGenerateConsole::OPTION_ORGANIZATION => 'Spryker',
        ]);

        // Assert
        $this->assertSame(AbstractConsole::CODE_SUCCESS, $commandTester->getStatusCode());
    }

    /**
     * @return void
     */
    public function testBuildFromAsyncApiRendersReferencedTransferProperties(): void
    {
        // Arrange
        $buildFromAsyncApiConsoleMock = $this->tester->getAsyncApiBuilderConsoleMock();

        $commandTester = $this->tester->getConsoleTester($buildFromAsyncApiConsoleMock);

        // Act
        $commandTester->execute([
            '--' . CodeGenerateConsole::OPTION_ASYNC_API_FILE => codecept_data_dir('api/valid/transfer_references_transfer.yml'),
            '--' . CodeGenerateConsole::OPTION_ORGANIZATION => 'Spryker',
        ], ['verbosity' => OutputInterface::VERBOSITY_VERBOSE]);

        // Assert
        $this->assertSame(AbstractConsole::CODE_SUCCESS, $commandTester->getStatusCode());

        $this->assertStringContainsString(AsyncApiInfo::addedPropertyWithTypeTo('foo', 'string', 'Message', 'ModuleName'), $commandTester->getDisplay());
        $this->assertStringContainsString(AsyncApiInfo::addedPropertyWithTypeTo('bar', 'string', 'Message', 'ModuleName'), $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function testBuildFromAsyncApiBackwardsCompatibilityWithOperationId(): void
    {
        // Arrange
        $buildFromAsyncApiConsoleMock = $this->tester->getAsyncApiBuilderConsoleMock();

        $commandTester = $this->tester->getConsoleTester($buildFromAsyncApiConsoleMock);

        // Act
        $commandTester->execute([
            '--' . CodeGenerateConsole::OPTION_ASYNC_API_FILE => codecept_data_dir('api/valid/BC_check-operation-id_asyncapi.schema.yml'),
            '--' . CodeGenerateConsole::OPTION_ORGANIZATION => 'Spryker',
        ], ['verbosity' => OutputInterface::VERBOSITY_VERBOSE]);

        // Assert
        $this->assertSame(AbstractConsole::CODE_SUCCESS, $commandTester->getStatusCode());
        $this->assertStringContainsString(AsyncApiInfo::addedPropertyWithTypeTo('firstName', 'string', 'Message', 'MyModule'), $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function testBuildFromAsyncApiPrintsResultToConsoleInVerboseMode(): void
    {
        // Arrange
        $buildFromAsyncApiConsoleMock = $this->tester->getAsyncApiBuilderConsoleMock();

        $commandTester = $this->tester->getConsoleTester($buildFromAsyncApiConsoleMock);

        // Act
        $commandTester->execute([
            '--' . CodeGenerateConsole::OPTION_ASYNC_API_FILE => codecept_data_dir('api/builder/asyncapi.yml'),
        ], ['verbosity' => OutputInterface::VERBOSITY_VERBOSE]);

        // Assert
        $this->assertSame(AbstractConsole::CODE_SUCCESS, $commandTester->getStatusCode());
        $this->assertStringContainsString(AsyncApiInfo::addedPropertyWithTypeTo('incomingSourceStatus', 'string', 'IncomingMessage', 'Module'), $commandTester->getDisplay());
        $this->assertStringContainsString(AsyncApiInfo::addedMessageHandlerPluginForMessageTo('IncomingMessage', 'Module'), $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function testBuildFromAsyncApiReturnsErrorCodeWhenAnErrorOccurred(): void
    {
        // Arrange
        $buildFromAsyncApiConsoleMock = $this->tester->getAsyncApiBuilderConsoleMock();

        $commandTester = $this->tester->getConsoleTester($buildFromAsyncApiConsoleMock);

        // Act
        $commandTester->execute([
            '--' . CodeGenerateConsole::OPTION_ASYNC_API_FILE => codecept_data_dir('api/builder/asyncapi-empty.yml'),
        ]);

        // Assert
        $this->assertSame(AbstractConsole::CODE_ERROR, $commandTester->getStatusCode());
    }

    /**
     * @return void
     */
    public function testBuildFromAsyncApiReturnsErrorCodeWhenAnErrorOccurredAndPrintsResultToConsoleInVerboseMode(): void
    {
        // Arrange
        $buildFromAsyncApiConsoleMock = $this->tester->getAsyncApiBuilderConsoleMock();

        $commandTester = $this->tester->getConsoleTester($buildFromAsyncApiConsoleMock);

        // Act
        $commandTester->execute([
            '--' . CodeGenerateConsole::OPTION_ASYNC_API_FILE => codecept_data_dir('api/builder/asyncapi-empty.yml'),
        ], ['verbosity' => OutputInterface::VERBOSITY_VERBOSE]);

        // Assert
        $this->assertSame(AbstractConsole::CODE_ERROR, $commandTester->getStatusCode());
        $this->assertStringContainsString(
            AsyncApiError::couldNotGenerateCodeFromAsyncApi(codecept_data_dir('api/builder/asyncapi-empty.yml')),
            $commandTester->getDisplay(),
        );
    }
}
