<?php
/**
 * This file is part of the Everon components.
 *
 * (c) Oliwier Ptak <oliwierptak@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Everon\Component\Utils\Collection;

trait ToArray
{

    /**
     * @param bool $deep
     *
     * @return array
     */
    public function toArray($deep = false)
    {
        $data = $this->getArrayableData();

        if ($deep) {
            foreach ($data as $key => $value) {
                if ($value instanceof ArrayableInterface) {
                    /* @var ArrayableInterface $value */
                    $data[$key] = $value->toArray($deep);
                }
            }
        }

        return $data;
    }

}
