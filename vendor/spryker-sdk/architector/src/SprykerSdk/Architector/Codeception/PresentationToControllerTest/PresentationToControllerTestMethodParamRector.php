<?php declare(strict_types = 1);

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Architector\Codeception\PresentationToControllerTest;

use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Type\ObjectType;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\Rector\AbstractRector;
use SprykerSdk\Architector\Codeception\RectorHelper\TestSuiteHelper;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class PresentationToControllerTestMethodParamRector extends AbstractRector
{
    /**
     * @var \Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger
     */
    private $phpDocTypeChanger;

    /**
     * @var \SprykerSdk\Architector\Codeception\RectorHelper\TestSuiteHelper
     */
    private $testSuiteHelper;

    /**
     * @param \Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger $phpDocTypeChanger
     * @param \SprykerSdk\Architector\Codeception\RectorHelper\TestSuiteHelper $testSuiteHelper
     */
    public function __construct(PhpDocTypeChanger $phpDocTypeChanger, TestSuiteHelper $testSuiteHelper)
    {
        $this->phpDocTypeChanger = $phpDocTypeChanger;
        $this->testSuiteHelper = $testSuiteHelper;
    }

    /**
     * @return array<class-string<\PhpParser\Node>>
     */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    /**
     * @param \PhpParser\Node\Stmt\ClassMethod $node
     *
     * @return \PhpParser\Node|null
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->testSuiteHelper->isSuite('Presentation', $node)) {
            return null;
        }

        $isModifiedNode = false;

        foreach ($node->getParams() as $param) {
            if ($param->type === null) {
                continue;
            }

            /** @var \PhpParser\Node\Name $name */
            $name = $param->type;
            $parts = $name->parts;
            $className = (string)array_pop($parts);

            if (strpos($className, 'PresentationTester') === false) {
                array_push($name->parts, $className);

                continue;
            }

            $className = str_replace(
                'PresentationTester',
                'ControllerTester',
                $className,
            );

            array_push($parts, $className);

            $newClassName = implode('\\', $parts);
            $this->refactorParamTypeHint($param, $newClassName);
            $this->refactorParamDocBlock($param, $node, $newClassName);

            $isModifiedNode = true;
        }

        if (!$isModifiedNode) {
            return null;
        }

        return $node;
    }

    /**
     * @param \PhpParser\Node\Param $param
     * @param string $className
     *
     * @return void
     */
    private function refactorParamTypeHint(Param $param, string $className): void
    {
        $fullyQualified = new FullyQualified($className);
        if ($param->type instanceof NullableType) {
            $param->type = new NullableType($fullyQualified);

            return;
        }

        $param->type = $fullyQualified;
    }

    /**
     * @param \PhpParser\Node\Param $param
     * @param \PhpParser\Node\Stmt\ClassMethod $classMethod
     * @param string $className
     *
     * @throws \Rector\Core\Exception\ShouldNotHappenException
     * @throws \Rector\Core\Exception\ShouldNotHappenException
     *
     * @return void
     */
    private function refactorParamDocBlock(Param $param, ClassMethod $classMethod, string $className): void
    {
        $type = new ObjectType($className);

        $paramName = $this->getName($param->var);

        if ($paramName === null) {
            throw new ShouldNotHappenException();
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($classMethod);
        $this->phpDocTypeChanger->changeParamType($phpDocInfo, $type, $param, $paramName);
    }

    /**
     * @return \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Refactors method arguments and doc blocks from using XPresentationTester to XControllerTester',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
namespace Foo\Presentation;
class SomeTest
{
    /**
     * @param XPresentationTester $i
     *
     * @return void
     */
    public function test(XPresentationTester $i) {}
}
CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
namespace Foo\Presentation;
class SomeTest
{
    /**
     * @param XControllerTester $i
     *
     * @return void
     */
    public function test(XControllerTester $i) {}
}
CODE_SAMPLE,
                ),
            ],
        );
    }
}
