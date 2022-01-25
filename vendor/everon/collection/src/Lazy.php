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

class Lazy extends Collection
{

    /**
     * @var \Closure
     */
    protected $LazyDataLoader = null;

    /**
     * @param \Closure $LazyDataLoader
     */
    public function __construct(\Closure $LazyDataLoader)
    {
        parent::__construct([]);
        $this->data = null;
        $this->LazyDataLoader = $LazyDataLoader;
    }

    /**
     * @return void
     */
    protected function actuate()
    {
        if ($this->data === null) {
            $this->data = $this->LazyDataLoader->__invoke() ?: [];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        $this->actuate();

        return parent::count();
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        $this->actuate();

        return parent::offsetExists($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        $this->actuate();

        return parent::offsetGet($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->actuate();
        parent::offsetSet($offset, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        $this->actuate();
        parent::offsetUnset($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        $this->actuate();

        return parent::getIterator();
    }

    /**
     * {@inheritdoc}
     */
    public function append($item)
    {
        $this->actuate();
        parent::append($item);
    }

    /**
     * {@inheritdoc}
     */
    public function appendArray(array $data)
    {
        $this->actuate();
        parent::appendArray($data);
    }

    /**
     * {@inheritdoc}
     */
    public function appendCollection(CollectionInterface $Collection)
    {
        $this->actuate();
        parent::appendCollection($Collection);
    }

    /**
     * {@inheritdoc}
     */
    public function collect(array $data)
    {
        $this->actuate();
        parent::collect($data);
    }

    /**
     * {@inheritdoc}
     */
    public function get($name, $default = null)
    {
        $this->actuate();

        return parent::get($name, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function has($name)
    {
        $this->actuate();

        return parent::has($name);
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        $this->actuate();

        return parent::isEmpty();
    }

    /**
     * {@inheritdoc}
     */
    public function remove($name)
    {
        $this->actuate();
        parent::remove($name);
    }

    /**
     * {@inheritdoc}
     */
    public function set($name, $value)
    {
        $this->actuate();
        parent::set($name, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray($deep = false)
    {
        $this->actuate();

        return parent::toArray($deep);
    }

    /**
     * {@inheritdoc}
     */
    public function sortValues($ascending = true, $flags = SORT_REGULAR)
    {
        $this->actuate();
        parent::sortValues($ascending, $flags);
    }

    /**
     * {@inheritdoc}
     */
    public function sortKeys($ascending = true, $flags = SORT_REGULAR)
    {
        $this->actuate();
        parent::sortKeys($ascending, $flags);
    }

    /**
     * {@inheritdoc}
     */
    public function sortBy(\Closure $sortRoutine)
    {
        $this->actuate();
        parent::sortBy($sortRoutine);
    }

}
