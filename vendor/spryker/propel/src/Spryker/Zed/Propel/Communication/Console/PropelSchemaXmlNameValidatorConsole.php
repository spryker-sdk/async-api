<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Propel\Business\PropelFacadeInterface getFacade()
 * @method \Spryker\Zed\Propel\Communication\PropelCommunicationFactory getFactory()
 */
class PropelSchemaXmlNameValidatorConsole extends Console
{
    /**
     * @var string
     */
    public const COMMAND_NAME = 'propel:schema:validate-xml-names';

    /**
     * @var string
     */
    public const DESCRIPTION = 'Validates XML element name rules for schema files.';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription(static::DESCRIPTION);

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $schemaValidationTransfer = $this->getFacade()->validateSchemaXmlFiles();
        if ($schemaValidationTransfer->getIsSuccess()) {
            return static::CODE_SUCCESS;
        }

        foreach ($schemaValidationTransfer->getValidationErrors() as $validationErrorTransfer) {
            $output->writeln($validationErrorTransfer->getMessage());
        }

        return static::CODE_ERROR;
    }
}
