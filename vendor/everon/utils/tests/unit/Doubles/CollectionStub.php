<?php
/**
 * This file is part of the Everon components.
 *
 * (c) Oliwier Ptak <everonphp@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Everon\Component\Utils\Tests\Unit\Doubles;

use Everon\Component\Utils\Collection\ArrayableInterface;
use Everon\Component\Utils\Collection\IsIterable;
use Everon\Component\Utils\Collection\MergeDefault;
use Everon\Component\Utils\Collection\ToArray;

class CollectionStub implements ArrayableInterface
{

    use ToArray;
    use IsIterable;
    use MergeDefault;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var array
     */
    protected $arrayable_data;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
        $this->arrayable_data = $data;
    }

    /**
     * @return array
     */
    protected function getArrayableData()
    {
        return $this->arrayable_data;
    }

    /**
     * @param array|\ArrayObject $data
     *
     * @return void
     */
    public function setData($data)
    {
        $this->arrayable_data = $data;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->arrayable_data;
    }

    /**
     * @return bool
     */
    public function canLoop()
    {
        return $this->collectionIsIterable($this->arrayable_data);
    }

    /**
     * @param $defaults
     *
     * @return void
     */
    public function mergeData($defaults)
    {
        $this->arrayable_data = $this->collectionMergeDefault($this->arrayable_data, $defaults);
    }

}
