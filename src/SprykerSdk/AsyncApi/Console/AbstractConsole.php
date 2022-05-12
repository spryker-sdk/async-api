<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\Console;

use ArrayObject;
use SprykerSdk\AsyncApi\AsyncApiConfig;
use SprykerSdk\AsyncApi\AsyncApiFacade;
use SprykerSdk\AsyncApi\AsyncApiFacadeInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

class AbstractConsole extends Command
{
    /**
     * @var int
     */
    public const CODE_SUCCESS = 0;

    /**
     * @var int
     */
    public const CODE_ERROR = 1;

    /**
     * @var \SprykerSdk\AsyncApi\AsyncApiConfig|null
     */
    protected ?AsyncApiConfig $config = null;

    /**
     * @var \SprykerSdk\AsyncApi\AsyncApiFacadeInterface|null
     */
    protected ?AsyncApiFacadeInterface $facade = null;

    /**
     * @param string|null $name
     * @param \SprykerSdk\AsyncApi\AsyncApiConfig|null $config
     */
    public function __construct(?string $name = null, ?AsyncApiConfig $config = null)
    {
        $this->config = $config;

        parent::__construct($name);
    }

    /**
     * @return \SprykerSdk\AsyncApi\AsyncApiConfig
     */
    protected function getConfig(): AsyncApiConfig
    {
        if ($this->config === null) {
            $this->config = new AsyncApiConfig();
        }

        return $this->config;
    }

    /**
     * @param \SprykerSdk\AsyncApi\AsyncApiFacadeInterface $asyncApiFacade
     *
     * @return void
     */
    public function setFacade(AsyncApiFacadeInterface $asyncApiFacade): void
    {
        $this->facade = $asyncApiFacade;
    }

    /**
     * @return \SprykerSdk\AsyncApi\AsyncApiFacadeInterface
     */
    protected function getFacade(): AsyncApiFacadeInterface
    {
        if ($this->facade === null) {
            $this->facade = new AsyncApiFacade();
        }

        return $this->facade;
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param \ArrayObject<int, \Generated\Shared\Transfer\MessageTransfer> $messageTransfers
     *
     * @return void
     */
    protected function printMessages(OutputInterface $output, ArrayObject $messageTransfers): void
    {
        if ($output->isVerbose()) {
            foreach ($messageTransfers as $messageTransfer) {
                $output->writeln($messageTransfer->getMessageOrFail());
            }
        }
    }
}
