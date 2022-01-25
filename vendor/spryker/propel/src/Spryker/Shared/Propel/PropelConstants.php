<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Propel;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface PropelConstants
{
    /**
     * Specification:
     * - Key for propel configuration.
     *
     * @api
     *
     * @var string
     */
    public const PROPEL = 'PROPEL';

    /**
     * Specification:
     * - key for propel configuration in
     *
     * @api
     *
     * @var string
     */
    public const PROPEL_DEBUG = 'PROPEL_DEBUG';

    /**
     * @deprecated Use {@link \Spryker\Shared\PropelOrm\PropelOrmConstants::PROPEL_SHOW_EXTENDED_EXCEPTION} instead.
     *
     * Specification:
     * - Enable this to get a better exception message when an error occurs.
     * - Should only be used on non production environments.
     *
     * @api
     *
     * @var string
     */
    public const PROPEL_SHOW_EXTENDED_EXCEPTION = 'PROPEL_SHOW_EXTENDED_EXCEPTION';

    /**
     * Specification:
     * - Name of database.
     *
     * @api
     *
     * @var string
     */
    public const ZED_DB_DATABASE = 'ZED_DB_DATABASE';

    /**
     * Specification:
     * - Name of engine which should be used.
     *
     * @api
     *
     * @var string
     */
    public const ZED_DB_ENGINE = 'ZED_DB_ENGINE';

    /**
     * Specification:
     * - Database host.
     *
     * @api
     *
     * @var string
     */
    public const ZED_DB_HOST = 'ZED_DB_HOST';

    /**
     * Specification:
     * - Database port number.
     *
     * @api
     *
     * @var string
     */
    public const ZED_DB_PORT = 'ZED_DB_PORT';

    /**
     * Specification:
     * - Database username.
     *
     * @api
     *
     * @var string
     */
    public const ZED_DB_USERNAME = 'ZED_DB_USERNAME';

    /**
     * Specification:
     * - Database password.
     *
     * @api
     *
     * @var string
     */
    public const ZED_DB_PASSWORD = 'ZED_DB_PASSWORD';

    /**
     * Specification:
     * - MySql database engine.
     *
     * @deprecated Will be removed without replacement.
     *
     * @api
     *
     * @var string
     */
    public const ZED_DB_ENGINE_MYSQL = 'ZED_DB_ENGINE_MYSQL';

    /**
     * Specification:
     * - Postgres database engine.
     *
     * @deprecated Will be removed without replacement.
     *
     * @api
     *
     * @var string
     */
    public const ZED_DB_ENGINE_PGSQL = 'ZED_DB_ENGINE_PGSQL';

    /**
     * Specification:
     * - Array of supported database engines.
     *
     * @deprecated Will be removed without replacement.
     *
     * @api
     *
     * @var string
     */
    public const ZED_DB_SUPPORTED_ENGINES = 'ZED_DB_SUPPORTED_ENGINES';

    /**
     * Specification:
     * - If this is enabled, create database command will be run as sudo.
     *
     * @api
     *
     * @var string
     */
    public const USE_SUDO_TO_MANAGE_DATABASE = 'USE_SUDO_TO_MANAGE_DATABASE';

    /**
     * Specification:
     * - Pattern for schema files path.
     * - Path is used with glob to find path.
     *
     * @deprecated Will be removed without replacement.
     *
     * @api
     *
     * @var string
     */
    public const SCHEMA_FILE_PATH_PATTERN = 'SCHEMA_FILE_PATH_PATTERN';

    /**
     * Specification:
     * - Path to log file.
     *
     * @api
     *
     * @var string
     */
    public const LOG_FILE_PATH = 'PROPEL:LOG_FILE_PATH';

    /**
     * Specification:
     * - Defines database connections to improve the performance of web applications by dispatching the database-load to multiple database-servers.
     *
     * Example:
     *
     * $config[PropelConstants::ZED_DB_REPLICAS][] = [
     *     'host' => 'replica-1',
     *     'port' => '5432'
     * ];
     *
     * - If slave-database connections are not set the Propel uses singular master database that is responsible for all read and write queries.
     *
     * Example:
     *
     * $config[PropelConstants::ZED_DB_REPLICAS] = [];
     *
     * @api
     *
     * @var string
     */
    public const ZED_DB_REPLICAS = 'PROPEL:ZED_DB_REPLICAS';
}
