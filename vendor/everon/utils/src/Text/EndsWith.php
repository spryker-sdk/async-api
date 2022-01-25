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

trait EndsWith
{

    /**
     * @param $string
     * @param $ends_with
     *
     * @return bool
     */
    protected function textEndsWith($string, $ends_with)
    {
        $ends_with = trim($ends_with);
        if ($ends_with === '') {
            return false;
        }

        return substr_compare($string, $ends_with, -strlen($ends_with), strlen($ends_with)) === 0;
    }

}
