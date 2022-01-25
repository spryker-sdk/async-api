<?php
/**
 * This file is part of the Everon components.
 *
 * (c) Oliwier Ptak <oliwierptak@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Everon\Component\Collection;

use Everon\Component\Utils\Collection\ArrayableInterface;

interface CollectionInterface extends ArrayableInterface, \Countable, \ArrayAccess, \IteratorAggregate
{

    /**
     * @param mixed $item
     *
     * @return self
     */
    public function append($item);

    /**
     * @param array $data
     *
     * @return self
     */
    public function appendArray(array $data);

    /**
     * @param CollectionInterface $Collection
     *
     * @return self
     */
    public function appendCollection(CollectionInterface $Collection);

    /**
     * @param array $data
     *
     * @return self
     */
    public function collect(array $data);

    /**
     * @param $name
     * @param null $default
     *
     * @return mixed|null
     */
    public function get($name, $default = null);

    /**
     * @param $name
     *
     * @return bool
     */
    public function has($name);

    /**
     * @return bool
     */
    public function isEmpty();

    /**
     * @param $name
     *
     * @return self
     */
    public function remove($name);

    /**
     * @param $name
     * @param $value
     *
     * @return self
     */
    public function set($name, $value);

    /**
     * @param bool|true $ascending
     * @param int $flags
     *
     * @return self
     */
    public function sortValues($ascending = true, $flags = SORT_REGULAR);

    /**
     * @param bool|true $ascending
     * @param int $flags
     *
     * @return self
     */
    public function sortKeys($ascending = true, $flags = SORT_REGULAR);

    /**
     * @param \Closure $sortRoutine
     *
     * @return self
     */
    public function sortBy(\Closure $sortRoutine);

}
