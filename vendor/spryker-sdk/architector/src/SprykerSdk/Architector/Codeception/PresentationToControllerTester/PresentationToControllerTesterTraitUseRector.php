<?php declare(strict_types = 1);

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Architector\Codeception\PresentationToControllerTester;

use PhpParser\Node;
use PhpParser\Node\Stmt\TraitUse;
use Rector\Core\Rector\AbstractRector;
use SprykerSdk\Architector\Codeception\RectorHelper\TestSuiteHelper;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class PresentationToControllerTesterTraitUseRector extends AbstractRector
{
    /**
     * @var \SprykerSdk\Architector\Codeception\RectorHelper\TestSuiteHelper
     */
    private $testSuiteHelper;

    /**
     * @param \SprykerSdk\Architector\Codeception\RectorHelper\TestSuiteHelper $testSuiteHelper
     */
    public function __construct(TestSuiteHelper $testSuiteHelper)
    {
        $this->testSuiteHelper = $testSuiteHelper;
    }

    /**
     * @return array<class-string<\PhpParser\Node>>
     */
    public function getNodeTypes(): array
    {
        return [TraitUse::class];
    }

    /**
     * @param \PhpParser\Node\Stmt\TraitUse $node
     *
     * @return \PhpParser\Node|null
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->testSuiteHelper->isPresentationTesterActions($node)) {
            return null;
        }

        $isModified = false;

        /** @var \PhpParser\Node\Name\FullyQualified $trait */
        foreach ($node->traits as $trait) {
            $traitNameParts = $trait->parts;
            $lastElement = (string)end($traitNameParts);
            if (strpos($lastElement, 'PresentationTesterActions') !== false) {
                $lastElement = str_replace('PresentationTesterActions', 'ControllerTesterActions', $lastElement);
                array_pop($trait->parts);
                array_push($trait->parts, $lastElement);

                $isModified = true;
            }
        }

        if ($isModified) {
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
            'Renames trait use from Presentation to Controller namespace',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
namespace Foo\Presentation;
class SomeTest
{
    use _generated\XPresentationTesterActions;
}
CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
namespace Foo\Presentation;
class SomeTest
{
    use _generated\XControllerTesterActions;
}
CODE_SAMPLE,
                ),
            ],
        );
    }
}
