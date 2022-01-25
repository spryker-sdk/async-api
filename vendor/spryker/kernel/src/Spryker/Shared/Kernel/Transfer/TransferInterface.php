<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\Transfer;

interface TransferInterface
{
    /**
     * @param bool $isRecursive
     *
     * @return array<string, mixed>
     */
    public function toArray($isRecursive = true);

    /**
     * @param bool $isRecursive
     *
     * @return array<string, mixed>
     */
    public function modifiedToArray($isRecursive = true);

    /**
     * @param array<string, mixed> $values
     * @param bool $fuzzyMatch
     *
     * @return $this
     */
    public function fromArray(array $values, $fuzzyMatch = false);

    /**
     * @param string $propertyName
     *
     * @return bool
     */
    public function isPropertyModified($propertyName);
}
