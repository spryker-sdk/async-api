<?php
/**
 * This file is part of the Everon components.
 *
 * (c) Oliwier Ptak <oliwierptak@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Everon\Component\Utils\Popo\Exception;

use Everon\Component\Utils\Exception\AbstractException;

class InvalidPropertyRequestedException extends AbstractException
{

    protected $message = 'Invalid property requested: "%s" with "%s" in "%s"';

};
