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

trait CamelToUnderscore
{

    /**
     * @param $string
     *
     * @return string
     */
    protected function textCamelToUnderscore($string)
    {
        $camelized_string_tokens = preg_split('/(?<=[^A-Z])(?=[A-Z])/', $string);
        if (count($camelized_string_tokens) > 0) {
            return mb_strtolower(implode('_', $camelized_string_tokens));
        }

        return $string;
    }

    /**
     * @param $string
     *
     * @return string
     */
    protected function textCamelToUnderscoreStripFirstToken($string)
    {
        $camelized_string_tokens = preg_split('/(?<=\\w)(?=[A-Z])/', $string);
        array_shift($camelized_string_tokens);
        if (count($camelized_string_tokens) > 0) {
            return mb_strtolower(implode('_', $camelized_string_tokens));
        }

        return $string;
    }

}
