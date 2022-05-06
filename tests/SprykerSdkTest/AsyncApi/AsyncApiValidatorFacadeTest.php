<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\AopSdk\Business;

use Codeception\Test\Unit;
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
        $this->assertEquals('No AsyncAPI file given, you need to pass a valid filename.', $expectedErrorMessage->getMessage(), 'Async API file "vfs://root/config/api/asyncapi/asyncapi.yml" not found');
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
        $this->assertEquals('Could not parse AsyncApi file.', $expectedErrorMessage->getMessage(), 'AsyncApi file "vfs://root/config/api/asyncapi/valid/invalid_base_asyncapi.schema.yml" is invalid. Error: "Syntax error".');
    }

    /**
     * @return void
     */
    public function testValidateAsyncApiReturnsFailedResponseWhenFileDoNotContainMessages(): void
    {
        // Arrange
        $this->tester->haveAsyncApiFileWithNoMessages();

        // Act
        $validateResponseTransfer = $this->tester->getFacade()->validateAsyncApi(
            $this->tester->haveValidateRequest(),
        );

        // Assert
        $expectedErrorMessage = $validateResponseTransfer->getErrors()[0];
        $this->assertEquals('Async API file does not contain messages.', $expectedErrorMessage->getMessage(), 'AsyncApi file "vfs://root/config/api/asyncapi/valid/invalid_base_asyncapi.schema.yml" does not contain messages.');
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
        $this->assertContains('Async API file has missing operationId.', $this->tester->getMessagesFromValidateResponseTransfer($validateResponseTransfer));
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
        $this->assertContains('Async API file contains duplicate message names.', $this->tester->getMessagesFromValidateResponseTransfer($validateResponseTransfer));
    }
}
