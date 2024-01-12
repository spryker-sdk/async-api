<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\AsyncApi;

use Codeception\Actor;
use SprykerSdk\AsyncApi\AsyncApiFacade;
use SprykerSdk\AsyncApi\AsyncApiFacadeInterface;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(\SprykerSdkTest\AsyncApi\PHPMD)
 */
class AsyncApiTester extends Actor
{
    use _generated\AsyncApiTesterActions;

    /**
     * @var \SprykerSdk\AsyncApi\AsyncApiFacadeInterface|null
     */
    protected ?AsyncApiFacadeInterface $asyncApiFacade = null;

    /**
     * @return \SprykerSdk\AsyncApi\AsyncApiFacadeInterface
     */
    public function getFacade(): AsyncApiFacadeInterface
    {
        if (!$this->asyncApiFacade) {
            $this->asyncApiFacade = new AsyncApiFacade();
        }

        return $this->asyncApiFacade;
    }
}
