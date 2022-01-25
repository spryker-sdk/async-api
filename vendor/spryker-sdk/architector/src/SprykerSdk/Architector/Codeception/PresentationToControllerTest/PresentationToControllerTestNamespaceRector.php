<?php declare(strict_types = 1);

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Architector\Codeception\PresentationToControllerTest;

use PhpParser\Node;
use PhpParser\Node\Stmt\Namespace_;
use Rector\Core\Rector\AbstractRector;
use SprykerSdk\Architector\Codeception\RectorHelper\TestSuiteHelper;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class PresentationToControllerTestNamespaceRector extends AbstractRector
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
        return [Namespace_::class];
    }

    /**
     * @param \PhpParser\Node\Stmt\Namespace_ $node
     *
     * @return \PhpParser\Node|null
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->testSuiteHelper->isSuite('Presentation', $node)) {
            return null;
        }

        /** @var \PhpParser\Node\Name $subNode */
        $subNode = $node->name;

        array_pop($subNode->parts);
        array_push($subNode->parts, 'Controller');

        return $node;
    }

    /**
     * @return \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Refactors namespace from Presentation to Controller',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
namespace Foo\Presentation;

class SomeTest
{
}
CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
namespace Foo\Controller;

class SomeTest
{
}
CODE_SAMPLE,
                ),
            ],
        );
    }
}
