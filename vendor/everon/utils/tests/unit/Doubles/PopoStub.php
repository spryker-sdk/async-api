<?php
/**
 * This file is part of the Everon components.
 *
 * (c) Oliwier Ptak <everonphp@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Everon\Component\Utils\Tests\Unit\Doubles;

use Everon\Component\Utils\Popo\Popo;

/**
 * @method int getFoo
 * @method int setFoo($foo)
 * @method string getBar
 * @method string getFuzzBarFoo
 */
class PopoStub extends Popo
{
}
