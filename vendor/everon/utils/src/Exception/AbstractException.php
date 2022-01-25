<?php
/**
 * This file is part of the Everon components.
 *
 * (c) Oliwier Ptak <oliwierptak@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Everon\Component\Utils\Exception;

use Everon\Component\Utils\Text\ToString;

abstract class AbstractException extends \Exception
{

    use ToString;

    protected $toString = null;

    /**
     * @param null $parameters
     * @param null $message
     * @param null $Previous
     * @param int $code
     */
    public function __construct($parameters = null, $message = null, $Previous = null, $code = 0)
    {
        if ($parameters instanceof \Exception) {
            $message = $parameters->getMessage();
        } elseif ($message === null) {
            $message = $this->message;
        }

        $message = $this->formatExceptionParams($message, $parameters);

        parent::__construct($message, $code, $Previous);
    }

    /**
     * @param string $message
     * @param mixed $parameters
     *
     * @return string
     */
    protected function formatExceptionParams($message, $parameters)
    {
        if (trim($message) === '' || $parameters === null) {
            return $message;
        }

        if (is_array($parameters) === false) {
            $parameters = [$parameters];
        }

        return @vsprintf($message, $parameters);
    }

    /**
     * @param \Exception $Exception
     *
     * @return string
     */
    public static function getErrorMessageFromException(\Exception $Exception)
    {
        $message = '';
        $exception_message = $Exception->getMessage();
        $class = get_class($Exception);
        if ($class !== '') {
            $message = $message . '{' . $class . '}';
        }
        if ($message !== '' && $exception_message !== '') {
            $message = $message . ' ';
        }
        $message = $message . $exception_message;

        return $message;
    }

    /**
     * @return string
     */
    protected function getToString()
    {
        return static::getErrorMessageFromException($this);
    }

}
