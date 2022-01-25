<?php
/**
 * This file is part of the Everon components.
 *
 * (c) Oliwier Ptak <everonphp@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Everon\Component\Utils\Popo;

use Everon\Component\Utils\Collection\ArrayableInterface;

interface PopoInterface extends ArrayableInterface
{

    /**
     * @return array
     */
    public function getData();

    /**
     * @param array $data
     */
    public function setData(array $data);

}
