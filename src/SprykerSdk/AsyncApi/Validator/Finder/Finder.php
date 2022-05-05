<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\Validator\Finder;

use Symfony\Component\Finder\Finder as SymfonyFinder;
use Symfony\Component\Finder\SplFileInfo;

class Finder implements FinderInterface
{

    /**
     * @param string|null $path
     *
     * @return bool
     */
    public function hasFiles(?string $path): bool
    {
        return $path && file_exists($path);
    }

    /**
     * @param string $path
     *
     * @return \Symfony\Component\Finder\SplFileInfo
     */
    public function getFile(string $path): SplFileInfo
    {
        $iterator = $this->getFinder(dirname($path), basename($path))->files()->getIterator();
        $iterator->rewind();

        return $iterator->current();
    }

    /**
     * @param string $path
     * @param string|null $fileName
     *
     * @return \Symfony\Component\Finder\Finder
     */
    protected function getFinder(string $path, ?string $fileName = null): SymfonyFinder
    {
        $finder = new SymfonyFinder();
        $finder->in($path);

        if ($fileName) {
            $finder->name($fileName);
        }

        return $finder;
    }
}
