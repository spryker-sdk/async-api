<?php
/**
 * This file is part of the Everon components.
 *
 * (c) Oliwier Ptak <everonphp@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Everon\Component\Utils\Collection;

trait IsIterable
{

    /**
     * @param mixed $input
     *
     * @return bool
     */
    protected function collectionIsIterable($input)
    {
        if (isset($input) && is_array($input)) {
            return true;
        }

        if ($input instanceof \ArrayAccess || $input instanceof \Iterator || $input instanceof \Traversable) {
            return true;
        }

        return false;
    }

}
