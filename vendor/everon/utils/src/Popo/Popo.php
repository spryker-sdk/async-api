<?php
/**
 * This file is part of the Everon components.
 *
 * (c) Oliwier Ptak <oliwierptak@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Everon\Component\Utils\Popo;

use Everon\Component\Utils\Popo\Exception\InvalidPropertyRequestedException;
use Everon\Component\Utils\Text\CamelToUnderscore;
use Everon\Component\Utils\Collection\ToArray;
use Everon\Component\Utils\Popo\Exception\InvalidMethodCallException;

/**
 * Plain Old PHP Object, data accessible only via method calls, eg. $Popo->getTitle(), $Popo->setTitle('title')
 *
 * @see http://en.wikipedia.org/wiki/POJO
 */
class Popo implements PopoInterface
{

    use CamelToUnderscore;
    use ToArray;

    const CALL_TYPE_GETTER = 1;
    const CALL_TYPE_SETTER = 2;

    /**
     * @var int
     */
    protected $call_type = null;

    /**
     * @var string
     */
    protected $call_property = null;

    /**
     * @var array
     */
    protected $property_name_cache = [];

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @param string $name
     * @param mixed $arguments
     *
     * @throws InvalidMethodCallException
     * @throws InvalidPropertyRequestedException
     *
     * @return null|mixed
     */
    public function __call($name, $arguments)
    {
        $this->call_type = null;
        $this->call_property = null;

        $getter = strpos($name, 'get') === 0;
        $setter = strpos($name, 'set') === 0;

        if ($getter) {
            $this->call_type = static::CALL_TYPE_GETTER;
        } elseif ($setter) {
            $this->call_type = static::CALL_TYPE_SETTER;
        }

        if ($setter === false && $getter === false) {
            throw new InvalidMethodCallException([
                $name . '()', get_called_class(),
            ]);
        }

        if (array_key_exists($name, $this->property_name_cache)) {
            $property = $this->property_name_cache[$name];
        } else {
            $property = $this->textCamelToUnderscoreStripFirstToken($name);
            $this->property_name_cache[$name] = $property;
        }

        $this->call_property = $property;

        if (array_key_exists($property, $this->data) === false) {
            throw new InvalidPropertyRequestedException([
                $property, $name . '()', get_called_class(),
            ]);
        }

        if ($getter) {
            return $this->data[$property];
        } elseif ($setter) {
            $this->data[$property] = $arguments[0];
        }

        return null;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
        $this->property_name_cache = [];
    }

}
