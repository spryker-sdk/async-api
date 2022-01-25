<?php
/**
 * This file is part of the Everon components.
 *
 * (c) Oliwier Ptak <everonphp@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Everon\Component\Collection;

use Everon\Component\Utils\Collection\ToArray;

class Collection implements CollectionInterface
{

    use ToArray;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var int
     */
    protected $position = 0;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
        $this->position = 0;
    }

    /**
     * Implement this method to feed toArray() with custom data
     *
     * @return array
     */
    protected function getArrayableData()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function append($item)
    {
        $this->offsetSet($this->count(), $item);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function appendArray(array $data)
    {
        $this->data += $data;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function appendCollection(CollectionInterface $Collection)
    {
        $this->data += $Collection->toArray();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function get($name, $default = null)
    {
        if ($this->has($name) === false) {
            return $default;
        }

        return $this->offsetGet($name);
    }

    /**
     * {@inheritdoc}
     */
    public function has($name)
    {
        return $this->offsetExists($name);
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        return empty($this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($name)
    {
        $this->offsetUnset($name);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function set($name, $value)
    {
        $this->offsetSet($name, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function sortValues($ascending = true, $flags = SORT_REGULAR)
    {
        if ($ascending) {
            asort($this->data, $flags);
        } else {
            arsort($this->data, $flags);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function sortKeys($ascending = true, $flags = SORT_REGULAR)
    {
        if ($ascending) {
            ksort($this->data, $flags);
        } else {
            krsort($this->data, $flags);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function sortBy(\Closure $sortRoutine)
    {
        uksort($this->data, $sortRoutine);

        return $this;
    }

}
