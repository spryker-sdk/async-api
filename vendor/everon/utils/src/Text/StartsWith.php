<?php
/**
 * This file is part of the Everon components.
 *
 * (c) Oliwier Ptak <oliwierptak@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Everon\Component\Utils\Text;

trait StartsWith
{

    /**
     * @param $string
     * @param $starts_with
     *
     * @return bool
     */
    protected function textStartsWith($string, $starts_with)
    {
        return mb_strpos($string, $starts_with) === 0;
    }

}
