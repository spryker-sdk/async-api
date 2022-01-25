<?php

declare (strict_types=1);

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Architector\Set\ValueObject;

use Rector\Set\Contract\SetListInterface;

final class ArchitectorSetList implements SetListInterface
{
    /**
     * @var string
     */
    public const CODECEPTION = __DIR__ . '/../../../sets/Codeception/presentation-to-controller-test.php';

    /**
     * @var string
     */
    public const RENAME = __DIR__ . '/../../../sets/Rename/rename-param-to-match-type.php';

    /**
     * @var string
     */
    public const TRIGGER_ERROR = __DIR__ . '/../../../sets/Rfc/TriggerError/config.php';
}
