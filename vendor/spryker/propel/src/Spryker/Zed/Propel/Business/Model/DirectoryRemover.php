<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model;

use Symfony\Component\Filesystem\Filesystem;

class DirectoryRemover implements DirectoryRemoverInterface
{
    /**
     * @var string
     */
    protected $directoryToRemove;

    /**
     * @param string $directoryToRemove
     */
    public function __construct($directoryToRemove)
    {
        $this->directoryToRemove = $directoryToRemove;
    }

    /**
     * @return void
     */
    public function execute()
    {
        if (is_dir($this->directoryToRemove)) {
            $filesystem = new Filesystem();
            $filesystem->remove($this->directoryToRemove);
        }
    }
}
