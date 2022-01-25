<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

use Codeception\Configuration;

trait ClassResolverTrait
{
    use LocatorHelperTrait;

    /**
     * @var array<array-key, string>
     */
    protected $coreNamespaces = [
        'Spryker',
        'SprykerShop',
        'SprykerSdk',
        'SprykerMiddleware',
        'SprykerEco',
    ];

    /**
     * @param string $classNamePattern
     * @param string $moduleName
     *
     * @return object|null
     */
    protected function resolveClass(string $classNamePattern, string $moduleName): ?object
    {
        $resolvedClassName = $this->resolveClassName($classNamePattern, $moduleName);

        if ($resolvedClassName === null) {
            return null;
        }

        return new $resolvedClassName();
    }

    /**
     * @param string $classNamePattern
     * @param string $moduleName
     *
     * @return string|null
     */
    protected function resolveClassName(string $classNamePattern, string $moduleName): ?string
    {
        $classNameCandidates = $this->getClassNameCandidates($classNamePattern, $moduleName);

        foreach ($classNameCandidates as $classNameCandidate) {
            if (class_exists($classNameCandidate)) {
                return $classNameCandidate;
            }
        }

        return null;
    }

    /**
     * @param string $classNamePattern
     * @param string $moduleName
     *
     * @return array<string>
     */
    protected function getClassNameCandidates(string $classNamePattern, string $moduleName): array
    {
        $config = Configuration::config();
        $namespaceParts = explode('\\', $config['namespace']);
        $classNameCandidates = [];
        $classNameCandidates[] = sprintf($classNamePattern, $this->trimTestNamespacePostfix($namespaceParts[0]), $namespaceParts[1], $moduleName);

        foreach ($this->coreNamespaces as $coreNamespace) {
            $classNameCandidates[] = sprintf($classNamePattern, $coreNamespace, $namespaceParts[1], $moduleName);
        }

        return $classNameCandidates;
    }

    /**
     * @param string $namespacePart
     *
     * @return string
     */
    protected function trimTestNamespacePostfix(string $namespacePart): string
    {
        return preg_replace('/Test$/', '', $namespacePart);
    }
}
