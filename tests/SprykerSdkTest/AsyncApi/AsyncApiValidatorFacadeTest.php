<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\AopSdk\Business;

use Codeception\Test\Unit;
use SprykerSdk\AsyncApi\Message\AsyncApiError;
use SprykerSdkTest\AsyncApi\AsyncApiTester;

/**
 * @group SprykerSdkTest
 * @group AsyncApi
 * @group AsyncApiFacadeTest
 */
class AsyncApiValidatorFacadeTest extends Unit
{
    /**
     * @var \SprykerSdkTest\AsyncApi\AsyncApiTester
     */
    protected AsyncApiTester $tester;

    /**
     * @return void
     */
    public function testValidateAsyncApiReturnsFailedResponseWhenFileNotFound(): void
    {
        // Act
        $validateResponseTransfer = $this->tester->getFacade()->validateAsyncApi(
            $this->tester->haveValidateRequest(),
        );

        // Assert
        $expectedErrorMessage = $validateResponseTransfer->getErrors()[0];
        $this->assertEquals(AsyncApiError::asyncApiFileDoesNotExist('vfs://root/resources/api/asyncapi.yml'), $expectedErrorMessage->getMessage(), 'Async API file "vfs://root/resources/api/asyncapi.yml" not found');
    }

    /**
     * @return void
     */
    public function testValidateAsyncApiReturnsFailedResponseWhenFileHasSyntaxError(): void
    {
        // Arrange
        $this->tester->haveAsyncApiFileSyntaxError();

        // Act
        $validateResponseTransfer = $this->tester->getFacade()->validateAsyncApi(
            $this->tester->haveValidateRequest(),
        );

        // Assert
        $expectedErrorMessage = $validateResponseTransfer->getErrors()[0];
        $this->assertEquals(AsyncApiError::couldNotParseAsyncApiFile('vfs://root/resources/api/asyncapi.yml'), $expectedErrorMessage->getMessage(), 'AsyncApi file "vfs://root/resources/api/asyncapi/valid/invalid_base_asyncapi.schema.yml" is invalid. Error: "Syntax error".');
    }

    /**
     * @return void
     */
    public function testValidateAsyncApiReturnsFailedResponseWhenFileDoNotContainMessages(): void
    {
        // Arrange
        $this->tester->haveDefaultCreatedAsyncApiFile();

        // Act
        $validateResponseTransfer = $this->tester->getFacade()->validateAsyncApi(
            $this->tester->haveValidateRequest(),
        );

        // Assert
        $expectedErrorMessage = $validateResponseTransfer->getErrors()[0];
        $this->assertEquals(AsyncApiError::asyncApiDoesNotDefineMessages(), $expectedErrorMessage->getMessage(), 'AsyncApi file "vfs://root/resources/api/asyncapi/valid/invalid_base_asyncapi.schema.yml" does not contain messages.');
    }

    /**
     * @return void
     */
    public function testValidateAsyncApiReturnsFailedResponseWhenFileExistsButARequiredFieldIsMissing(): void
    {
        // Arrange
        $this->tester->haveAsyncApiFileWithMissingRequiredFields();

        // Act
        $validateResponseTransfer = $this->tester->getFacade()->validateAsyncApi(
            $this->tester->haveValidateRequest(),
        );

        // Assert
        $messages = $this->tester->getMessagesFromValidateResponseTransfer($validateResponseTransfer);
        $this->assertContains(AsyncApiError::messageDoesNotHaveAnOperationId('OutgoingMessage'), $messages, sprintf("Messages: \n\n%s\n", implode(PHP_EOL, $messages)));
    }

    /**
     * @return void
     */
    public function testValidateAsyncApiReturnsFailedResponseWhenMessageNameIsUsedMoreThanOnce(): void
    {
        // Arrange
        $this->tester->haveAsyncApiFileWithDuplicatedMessageNames();

        // Act
        $validateResponseTransfer = $this->tester->getFacade()->validateAsyncApi(
            $this->tester->haveValidateRequest(),
        );

        // Assert
        $messages = $this->tester->getMessagesFromValidateResponseTransfer($validateResponseTransfer);
        $this->assertContains(AsyncApiError::messageNameUsedMoreThanOnce('OutgoingMessage'), $messages, sprintf("Messages: \n\n%s\n", implode(PHP_EOL, $messages)));
        $this->assertContains(AsyncApiError::messageNameUsedMoreThanOnce('IncomingMessage'), $messages, sprintf("Messages: \n\n%s\n", implode(PHP_EOL, $messages)));
    }
}
