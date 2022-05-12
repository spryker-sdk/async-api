<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\Console;

use Generated\Shared\Transfer\AsyncApiChannelTransfer;
use Generated\Shared\Transfer\AsyncApiMessageTransfer;
use Generated\Shared\Transfer\AsyncApiRequestTransfer;
use SprykerSdk\AsyncApi\Exception\InvalidConfigurationException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SchemaMessageAddConsole extends AbstractConsole
{
    /**
     * @var string
     */
    public const OPTION_ASYNC_API_FILE = 'asyncapi-file';

    /**
     * @var string
     */
    public const OPTION_ASYNC_API_FILE_SHORT = 'a';

    /**
     * @var string
     */
    public const ARGUMENT_CHANNEL_NAME = 'channel-name';

    /**
     * @var string
     */
    public const ARGUMENT_MESSAGE_NAME = 'message-name';

    /**
     * @var string
     */
    public const OPTION_FROM_TRANSFER_CLASS = 'from-transfer-class';

    /**
     * @var string
     */
    public const OPTION_FROM_TRANSFER_CLASS_SHORT = 't';

    /**
     * @var string
     */
    public const OPTION_MESSAGE_TYPE = 'message-type';

    /**
     * @var string
     */
    public const OPTION_MESSAGE_TYPE_SHORT = 'e';

    /**
     * @var string
     */
    public const OPTION_ADD_METADATA = 'add-metadata';

    /**
     * @var string
     */
    public const OPTION_ADD_METADATA_SHORT = 'd';

    /**
     * @var string
     */
    public const OPTION_PROPERTY = 'property';

    /**
     * @var string
     */
    public const OPTION_PROPERTY_SHORT = 'P';

    /**
     * @var string
     */
    public const ARGUMENT_OPERATION_ID = 'operation-id';

    /**
     * @var string
     */
    public const VALUE_PUBLISH = 'publish';

    /**
     * @var string
     */
    public const VALUE_SUBSCRIBE = 'subscribe';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('schema:asyncapi:message:add')
            ->setDescription('Adds a message definition to a specified Async API schema file.')
            ->addArgument(static::ARGUMENT_CHANNEL_NAME, InputArgument::REQUIRED, 'The channel name to which the message should be sent.')
            ->addArgument(static::ARGUMENT_MESSAGE_NAME, InputOption::VALUE_REQUIRED, 'Name of the message e.g ProductUpdated.')
            ->addArgument(static::ARGUMENT_OPERATION_ID, InputOption::VALUE_REQUIRED, 'The module name that will work with the message.')
            ->addOption(static::OPTION_ASYNC_API_FILE, static::OPTION_ASYNC_API_FILE_SHORT, InputOption::VALUE_REQUIRED, '', $this->getConfig()->getDefaultAsyncApiFile())
            ->addOption(static::OPTION_PROPERTY, static::OPTION_PROPERTY_SHORT, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'When this option is set the property value will be added to the message definition of the specified channel. Format: propertyName:propertyType. Example: -P firstName:string')
            ->addOption(static::OPTION_FROM_TRANSFER_CLASS, static::OPTION_FROM_TRANSFER_CLASS_SHORT, InputOption::VALUE_REQUIRED, 'The Transfer class name from which the message should be created.')
            ->addOption(static::OPTION_MESSAGE_TYPE, static::OPTION_MESSAGE_TYPE_SHORT, InputOption::VALUE_REQUIRED, 'When this option is set the message will be added to the `publish` or `subscribe` part of the specified channel (Others can publish or subscribe to).')
            ->addOption(static::OPTION_ADD_METADATA, static::OPTION_ADD_METADATA_SHORT, InputOption::VALUE_NONE, 'When this option is set the defined default set of metadata will be added to the message definition.');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @throws \SprykerSdk\Aop\Exception\InvalidConfigurationException
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $asyncApiRequestTransfer = new AsyncApiRequestTransfer();
        $asyncApiRequestTransfer->setTargetFile($input->getOption(static::OPTION_ASYNC_API_FILE));

        $asyncApiChannelTransfer = new AsyncApiChannelTransfer();
        $asyncApiChannelTransfer->setName($input->getArgument(static::ARGUMENT_CHANNEL_NAME));

        $messageType = $input->getOption(static::OPTION_MESSAGE_TYPE);

        if (!in_array($messageType, [static::VALUE_PUBLISH, static::VALUE_SUBSCRIBE])) {
            throw new InvalidConfigurationException(
                sprintf(
                    '%s must be %s or %s',
                    static::OPTION_MESSAGE_TYPE,
                    static::VALUE_PUBLISH,
                    static::VALUE_SUBSCRIBE,
                ),
            );
        }

        $asyncApiMessageTransfer = new AsyncApiMessageTransfer();
        $asyncApiMessageTransfer
            ->setChannel($asyncApiChannelTransfer)
            ->setName($input->getArgument(static::ARGUMENT_MESSAGE_NAME))
            ->setAddMetadata($input->getOption(static::OPTION_ADD_METADATA))
            ->setIsPublish($messageType === static::VALUE_PUBLISH ?: false)
            ->setIsSubscribe($messageType === static::VALUE_SUBSCRIBE ?: false);

        $asyncApiRequestTransfer->setAsyncApiMesssage($asyncApiMessageTransfer);

        $asyncApiRequestTransfer->setPayloadTransferObjectName($input->getOption(static::OPTION_FROM_TRANSFER_CLASS));
        $asyncApiRequestTransfer->setProperties($input->getOption(static::OPTION_PROPERTY));
        $asyncApiRequestTransfer->setOperationId($input->getArgument(static::ARGUMENT_OPERATION_ID));

        $asyncApiResponseTransfer = $this->getFacade()->addAsyncApiMessage($asyncApiRequestTransfer);

        if ($asyncApiResponseTransfer->getErrors()->count() === 0) {
            $this->printMessages($output, $asyncApiResponseTransfer->getMessages());

            return static::CODE_SUCCESS;
        }

        $this->printMessages($output, $asyncApiResponseTransfer->getErrors());

        return static::CODE_ERROR;
    }
}
