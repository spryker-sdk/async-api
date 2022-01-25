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

interface ArrayableInterface
{

    /**
     * @param bool $deep
     *
     * @return array
     */
    public function toArray($deep = false);

}
