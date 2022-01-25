<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Architector\Codeception\PresentationToControllerConfig;

use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\Configuration;

final class PresentationToControllerConfigFileProcessor implements FileProcessorInterface
{
    /**
     * @var array<\SprykerSdk\Architector\Codeception\PresentationToControllerConfig\PresentationToControllerConfigRectorInterface>
     */
    private $rectors;

    /**
     * @param array<\SprykerSdk\Architector\Codeception\PresentationToControllerConfig\PresentationToControllerConfigRectorInterface> $rectors
     */
    public function __construct(array $rectors)
    {
        $this->rectors = $rectors;
    }

    /**
     * @param \Rector\Core\ValueObject\Application\File $file
     * @param \Rector\Core\ValueObject\Configuration $configuration
     *
     * @return bool
     */
    public function supports(File $file, Configuration $configuration): bool
    {
        $smartFileInfo = $file->getSmartFileInfo();

        return (strpos($smartFileInfo->getFilename(), 'codeception.yml') !== false);
    }

    /**
     * @param \Rector\Core\ValueObject\Application\File $file
     * @param \Rector\Core\ValueObject\Configuration $configuration
     *
     * @return array
     */
    public function process(File $file, Configuration $configuration): array
    {
        foreach ($this->rectors as $rector) {
            $changeFileContent = $rector->transform($file->getFileContent());
            $file->changeFileContent($changeFileContent);
        }

        return [];
    }

    /**
     * @return array<string>
     */
    public function getSupportedFileExtensions(): array
    {
        return ['yml'];
    }
}
