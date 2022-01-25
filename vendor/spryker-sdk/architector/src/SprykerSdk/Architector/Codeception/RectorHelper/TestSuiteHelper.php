<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Architector\Codeception\RectorHelper;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\TraitUse;
use Rector\NodeTypeResolver\Node\AttributeKey;

class TestSuiteHelper
{
    /**
     * @param \PhpParser\Node $node
     *
     * @return bool
     */
    public function isPresentationTesterClass(Node $node): bool
    {
        if ($node instanceof Class_ && strpos((string)$node->name, 'PresentationTester') !== false) {
            return true;
        }

        return false;
    }

    /**
     * @param \PhpParser\Node $node
     *
     * @return bool
     */
    public function isPresentationTesterActions(Node $node): bool
    {
        if ($node instanceof TraitUse) {
            /** @var \PhpParser\Node\Name\FullyQualified $trait */
            foreach ($node->traits as $trait) {
                $traitNameParts = $trait->parts;
                $lastElement = (string)end($traitNameParts);
                if (strpos($lastElement, 'PresentationTesterActions') !== false) {
                    return true;
                }
            }

            return false;
        }

        return false;
    }

    /**
     * @param string $expectedSuiteName
     * @param \PhpParser\Node $node
     *
     * @return bool
     */
    public function isSuite(string $expectedSuiteName, Node $node): bool
    {
        if ($node instanceof Class_) {
            $namespacedNameParts = $node->namespacedName->parts;

            if ((isset($namespacedNameParts[3]) && $namespacedNameParts[3] === $expectedSuiteName)) {
                return true;
            }

            return false;
        }

        if ($node instanceof ClassMethod) {
            /** @var \PhpParser\Node\Stmt\Class_ $parentNode */
            $parentNode = $node->getAttribute(AttributeKey::PARENT_NODE);

            $namespacedNameParts = $parentNode->namespacedName->parts;

            if ($parentNode instanceof Class_ && (isset($namespacedNameParts[3]) && $namespacedNameParts[3] === $expectedSuiteName)) {
                return true;
            }

            return false;
        }

        if ($node instanceof Namespace_) {
            if ($node->name === null) {
                return false;
            }

            $lastNamespaceElement = end($node->name->parts);

            if ($lastNamespaceElement === 'Presentation') {
                return true;
            }

            return false;
        }

        return false;
    }
}
