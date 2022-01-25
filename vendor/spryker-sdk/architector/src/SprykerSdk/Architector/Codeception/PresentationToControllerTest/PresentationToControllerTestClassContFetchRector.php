<?php declare(strict_types = 1);

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Architector\Codeception\PresentationToControllerTest;

use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Name\FullyQualified;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class PresentationToControllerTestClassContFetchRector extends AbstractRector
{
    /**
     * @return array<class-string<\PhpParser\Node>>
     */
    public function getNodeTypes(): array
    {
        return [ClassConstFetch::class];
    }

    /**
     * @param \PhpParser\Node\Expr\ClassConstFetch $node
     *
     * @return \PhpParser\Node|null
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->class instanceof FullyQualified && strpos((string)end($node->class->parts), 'PresentationTester') !== false) {
            $classNameShort = (string)array_pop($node->class->parts);
            $classNameShort = str_replace('PresentationTester', 'ControllerTester', $classNameShort);
            array_push($node->class->parts, $classNameShort);

            return $node;
        }

        return null;
    }

    /**
     * @return \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace usages of XPresentationTester::const with XControllerTester::const',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$foo = XPresentationTester::const;
CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
$foo = XControllerTester::const;
CODE_SAMPLE,
                ),
            ],
        );
    }
}
