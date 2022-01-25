<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Log\Business\Model;

use Symfony\Component\Filesystem\Filesystem;

class LogClear implements LogClearInterface
{
    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * @var array<string>
     */
    protected $logFileDirectories = [];

    /**
     * @param \Symfony\Component\Filesystem\Filesystem $filesystem
     * @param array<string> $logFileDirectories
     */
    public function __construct(Filesystem $filesystem, array $logFileDirectories)
    {
        $this->filesystem = $filesystem;
        $this->logFileDirectories = $logFileDirectories;
    }

    /**
     * @return void
     */
    public function clearLogs()
    {
        foreach ($this->logFileDirectories as $logFileDirectory) {
            $this->removeLogFiles($logFileDirectory);
        }
    }

    /**
     * @param string $logFileDirectory
     *
     * @return void
     */
    protected function removeLogFiles(string $logFileDirectory): void
    {
        if (!$this->filesystem->exists($logFileDirectory)) {
            return;
        }

        $this->filesystem->remove($logFileDirectory);
    }
}
