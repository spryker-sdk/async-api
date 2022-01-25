<?php declare(strict_types = 1);

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Architector\Codeception\PresentationToControllerTester;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Rector\Core\Rector\AbstractRector;
use SprykerSdk\Architector\Codeception\RectorHelper\TestSuiteHelper;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class PresentationToControllerTesterClassNameRector extends AbstractRector
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
        return [Class_::class];
    }

    /**
     * @param \PhpParser\Node\Stmt\Class_ $node
     *
     * @return \PhpParser\Node|null
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->testSuiteHelper->isPresentationTesterClass($node)) {
            return null;
        }

        /** @var \PhpParser\Node\Identifier $subNode */
        $subNode = $node->name;
        $subNode->name = str_replace('PresentationTester', 'ControllerTester', $subNode->name);

        return $node;
    }

    /**
     * @return \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Renames PresentationTester to ControllerTester',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
namespace Foo\Presentation;
class SomePresentationTester
{
}
CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
namespace Foo\Controller;
class SomeControllerTester
{
}
CODE_SAMPLE,
                ),
            ],
        );
    }
}
