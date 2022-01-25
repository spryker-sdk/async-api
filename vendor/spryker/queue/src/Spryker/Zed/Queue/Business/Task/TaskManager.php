<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business\Task;

use Generated\Shared\Transfer\QueueTaskResponseTransfer;
use Spryker\Client\Queue\QueueClientInterface;
use Spryker\Shared\Queue\QueueConfig as SharedConfig;
use Spryker\Zed\Queue\Business\Exception\MissingQueuePluginException;
use Spryker\Zed\Queue\QueueConfig;

class TaskManager implements TaskManagerInterface
{
    /**
     * @var \Spryker\Client\Queue\QueueClientInterface
     */
    protected $client;

    /**
     * @var \Spryker\Zed\Queue\QueueConfig
     */
    protected $queueConfig;

    /**
     * @var array<\Spryker\Zed\Queue\Dependency\Plugin\QueueMessageProcessorPluginInterface>
     */
    protected $messageProcessorPlugins;

    /**
     * @param \Spryker\Client\Queue\QueueClientInterface $client
     * @param \Spryker\Zed\Queue\QueueConfig $queueConfig
     * @param array<\Spryker\Zed\Queue\Dependency\Plugin\QueueMessageProcessorPluginInterface> $messageProcessorPlugins
     */
    public function __construct(QueueClientInterface $client, QueueConfig $queueConfig, array $messageProcessorPlugins)
    {
        $this->client = $client;
        $this->queueConfig = $queueConfig;
        $this->messageProcessorPlugins = $messageProcessorPlugins;
    }

    /**
     * @param string $queueName
     * @param array $options
     *
     * @return \Generated\Shared\Transfer\QueueTaskResponseTransfer
     */
    public function run($queueName, array $options = []): QueueTaskResponseTransfer
    {
        $queueTaskResponseTransfer = new QueueTaskResponseTransfer();
        $queueTaskResponseTransfer->setIsSuccesful(false);

        $processorPlugin = $this->getQueueProcessorPlugin($queueName);
        $queueOptions = $this->getQueueReceiverOptions($queueName);
        $messages = $this->receiveMessages($queueName, $processorPlugin->getChunkSize(), $queueOptions);

        if (!$messages) {
            $queueTaskResponseTransfer->setMessage(sprintf('No messages received from the queue "%s".', $queueName));

            return $queueTaskResponseTransfer;
        }

        $queueTaskResponseTransfer->setReceivedMessageCount(count($messages));

        $processedMessages = $processorPlugin->processMessages($messages);

        if (!$processedMessages) {
            $queueTaskResponseTransfer->setMessage(sprintf(
                'No messages processed from the queue "%s". Wether there is nothing to process or something failed while processing.',
                $queueName
            ));

            return $queueTaskResponseTransfer;
        }

        $queueTaskResponseTransfer->setProcessedMessageCount(count($processedMessages));

        $this->postProcessMessages($processedMessages, $options);

        $queueTaskResponseTransfer->setIsSuccesful(true);
        $queueTaskResponseTransfer->setMessage(sprintf(
            'Received messages: "%s", Processed messages: "%s"',
            count($messages),
            count($processedMessages)
        ));

        return $queueTaskResponseTransfer;
    }

    /**
     * @param string $queueName
     *
     * @throws \Spryker\Zed\Queue\Business\Exception\MissingQueuePluginException
     *
     * @return \Spryker\Zed\Queue\Dependency\Plugin\QueueMessageProcessorPluginInterface
     */
    protected function getQueueProcessorPlugin($queueName)
    {
        if (!array_key_exists($queueName, $this->messageProcessorPlugins)) {
            throw new MissingQueuePluginException(
                sprintf(
                    'There is no message processor plugin registered for this queue: %s, ' .
                    'you can fix this error by adding it in QueueDependencyProvider',
                    $queueName
                )
            );
        }

        return $this->messageProcessorPlugins[$queueName];
    }

    /**
     * @param string $queueName
     *
     * @return array
     */
    protected function getQueueReceiverOptions($queueName)
    {
        return $this->queueConfig->getQueueReceiverOption($queueName);
    }

    /**
     * @param string $queueName
     * @param int $chunkSize
     * @param array $options
     *
     * @return array<\Generated\Shared\Transfer\QueueReceiveMessageTransfer>
     */
    public function receiveMessages($queueName, $chunkSize, array $options = [])
    {
        return $this->client->receiveMessages($queueName, $chunkSize, $options);
    }

    /**
     * @param array<\Generated\Shared\Transfer\QueueReceiveMessageTransfer> $queueReceiveMessageTransfers
     * @param array $options
     *
     * @return void
     */
    protected function postProcessMessages(array $queueReceiveMessageTransfers, array $options = [])
    {
        if (isset($options[SharedConfig::CONFIG_QUEUE_OPTION_NO_ACK]) && $options[SharedConfig::CONFIG_QUEUE_OPTION_NO_ACK]) {
            return;
        }

        foreach ($queueReceiveMessageTransfers as $queueReceiveMessageTransfer) {
            if ($queueReceiveMessageTransfer->getAcknowledge()) {
                $this->client->acknowledge($queueReceiveMessageTransfer);
            }

            if ($queueReceiveMessageTransfer->getReject()) {
                $this->client->reject($queueReceiveMessageTransfer);
            }

            if ($queueReceiveMessageTransfer->getHasError()) {
                $this->client->handleError($queueReceiveMessageTransfer);
            }
        }
    }
}
