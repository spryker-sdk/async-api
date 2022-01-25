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

trait MergeDefault
{

    /**
     * @param array $default
     * @param array $data
     *
     * @return array
     */
    protected function collectionMergeDefault(array $default, array $data)
    {
        foreach ($default as $name => $value) {
            if (is_array($value)) {
                $value_data = isset($data[$name]) ? $data[$name] : [];
                $data[$name] = $this->collectionMergeDefault($default[$name], (array) $value_data);
            } else {
                if (array_key_exists($name, $data) === false) {
                    $data[$name] = $default[$name];
                }
            }
        }

        return $data;
    }

}
