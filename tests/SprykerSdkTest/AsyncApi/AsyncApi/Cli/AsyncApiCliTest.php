<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\AsyncApi\AsyncApi\Cli;

use Codeception\Test\Unit;
use SprykerSdk\AsyncApi\AsyncApi\Cli\AsyncApiCli;
use SprykerSdk\AsyncApi\AsyncApiConfig;
use SprykerSdk\AsyncApi\Message\AsyncApiError;
use SprykerSdk\AsyncApi\Message\AsyncApiInfo;
use SprykerSdk\AsyncApi\Message\MessageBuilder;
use SprykerSdkTest\AsyncApi\AsyncApiTester;
use Transfer\ValidateResponseTransfer;

/**
 * @group SprykerSdkTest
 * @group AsyncApi
 * @group AsyncApiCli
 * @group AsyncApiCliTest
 */
class AsyncApiCliTest extends Unit
{
    /**
     * @var \SprykerSdkTest\AsyncApi\AsyncApiTester
     */
    protected AsyncApiTester $tester;

    /**
     * @return void
     */
    public function testNoAsyncApiInstalledWhenIRunTheValidationThenISeeAMessageWithInstallInstructions(): void
    {
        // Arrange
        $asyncApiCliMock = $this->getAsyncApiCliMock(['runProcess']);
        $asyncApiCliMock->method('runProcess')->willReturn(false);

        // Act
        $validateResponseTransfer = $asyncApiCliMock->validate((new ValidateResponseTransfer()), '/');

        $validateResponseTransferMessages = $validateResponseTransfer->getMessages();

        // Assert
        $this->assertCount(1, $validateResponseTransferMessages);
        $this->assertSame(AsyncApiInfo::asyncApiCliNotFound(), $validateResponseTransferMessages->offsetGet(0)->getMessage());
    }

    /**
     * @return void
     */
    public function testAsyncApiIsInstalledWhenIRunTheValidationOnAnInvalidFileThenISeeAnErrorMessage(): void
    {
        // Arrange
        $asyncApiCliMock = $this->getAsyncApiCliMock(['runProcess']);
        $asyncApiCliMock->method('runProcess')->willReturnOnConsecutiveCalls(true, false);

        // Act
        $validateResponseTransfer = $asyncApiCliMock->validate((new ValidateResponseTransfer()), 'somePath');

        $validateResponseTransferErrors = $validateResponseTransfer->getErrors();

        // Assert
        $this->assertCount(1, $validateResponseTransferErrors);
        $this->assertSame(AsyncApiError::asyncApiCliValidationFailed('somePath'), $validateResponseTransferErrors->offsetGet(0)->getMessage());
    }

    /**
     * @return void
     */
    public function testNoAsyncApiInstalledWhenIRunTheValidationTriggerDeprecationWarning(): void
    {
        // Arrange
        $asyncApiCliMock = $this->getAsyncApiCliMock(['runProcess', 'triggerError']);
        $asyncApiCliMock->method('runProcess')->willReturn(false);

        // Expectation
        $asyncApiCliMock->expects($this->once())->method('triggerError');

        // Act
        $asyncApiCliMock->validate((new ValidateResponseTransfer()), '/');
    }

    /**
     * @return void
     */
    public function testAsyncApiIsInstalledWhenIRunTheValidationOnAnValidFileThenIDontSeeAnErrorMessage(): void
    {
        // Arrange
        $asyncApiCliMock = $this->getAsyncApiCliMock(['runProcess']);
        $asyncApiCliMock->method('runProcess')->willReturn(true);

        // Act
        $validateResponseTransfer = $asyncApiCliMock->validate((new ValidateResponseTransfer()), codecept_data_dir('api/valid/base_asyncapi.schema.yml'));

        $validateResponseTransferErrors = $validateResponseTransfer->getErrors();

        // Assert
        $this->assertCount(0, $validateResponseTransferErrors);
    }

    /**
     * @param array $methods
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|(\SprykerSdk\AsyncApi\AsyncApi\Cli\AsyncApiCli&\PHPUnit\Framework\MockObject\MockObject)
     */
    public function getAsyncApiCliMock(array $methods): AsyncApiCli|\PHPUnit\Framework\MockObject\MockObject
    {
        return $this->getMockBuilder(AsyncApiCli::class)->setConstructorArgs([new AsyncApiConfig(), new MessageBuilder()])->onlyMethods($methods)->getMock();
    }
}
