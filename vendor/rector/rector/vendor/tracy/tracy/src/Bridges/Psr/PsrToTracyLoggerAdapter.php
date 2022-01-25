<?php

/**
 * This file is part of the Tracy (https://tracy.nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */
declare (strict_types=1);
namespace RectorPrefix20220117\Tracy\Bridges\Psr;

use RectorPrefix20220117\Psr;
use RectorPrefix20220117\Tracy;
/**
 * Psr\Log\LoggerInterface to Tracy\ILogger adapter.
 */
class PsrToTracyLoggerAdapter implements \RectorPrefix20220117\Tracy\ILogger
{
    /** Tracy logger level to PSR-3 log level mapping */
    private const LEVEL_MAP = [\RectorPrefix20220117\Tracy\ILogger::DEBUG => \RectorPrefix20220117\Psr\Log\LogLevel::DEBUG, \RectorPrefix20220117\Tracy\ILogger::INFO => \RectorPrefix20220117\Psr\Log\LogLevel::INFO, \RectorPrefix20220117\Tracy\ILogger::WARNING => \RectorPrefix20220117\Psr\Log\LogLevel::WARNING, \RectorPrefix20220117\Tracy\ILogger::ERROR => \RectorPrefix20220117\Psr\Log\LogLevel::ERROR, \RectorPrefix20220117\Tracy\ILogger::EXCEPTION => \RectorPrefix20220117\Psr\Log\LogLevel::ERROR, \RectorPrefix20220117\Tracy\ILogger::CRITICAL => \RectorPrefix20220117\Psr\Log\LogLevel::CRITICAL];
    /** @var Psr\Log\LoggerInterface */
    private $psrLogger;
    public function __construct(\RectorPrefix20220117\Psr\Log\LoggerInterface $psrLogger)
    {
        $this->psrLogger = $psrLogger;
    }
    public function log($value, $level = self::INFO)
    {
        if ($value instanceof \Throwable) {
            $message = \RectorPrefix20220117\Tracy\Helpers::getClass($value) . ': ' . $value->getMessage() . ($value->getCode() ? ' #' . $value->getCode() : '') . ' in ' . $value->getFile() . ':' . $value->getLine();
            $context = ['exception' => $value];
        } elseif (!\is_string($value)) {
            $message = \trim(\RectorPrefix20220117\Tracy\Dumper::toText($value));
            $context = [];
        } else {
            $message = $value;
            $context = [];
        }
        $this->psrLogger->log(self::LEVEL_MAP[$level] ?? \RectorPrefix20220117\Psr\Log\LogLevel::ERROR, $message, $context);
    }
}
