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

trait ToString
{

    public function __toString()
    {
        try {
            if (method_exists($this, 'getToString')) {
                return $this->getToString();
            }

            return '';
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

}
