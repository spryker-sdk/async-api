<?php declare(strict_types = 1);

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Architector\Rename;

use ArrayObject;
use Exception;
use PhpParser\Node;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use Propel\Runtime\Collection\ObjectCollection;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\MethodName;
use Rector\Naming\ExpectedNameResolver\MatchParamTypeExpectedNameResolver;
use Rector\Naming\Guard\BreakingVariableRenameGuard;
use Rector\Naming\Naming\ExpectedNameResolver;
use Rector\Naming\ParamRenamer\ParamRenamer;
use Rector\Naming\ValueObject\ParamRename;
use Rector\Naming\ValueObjectFactory\ParamRenameFactory;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Throwable;

class RenameParamToMatchTypeRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var string
     */
    public const CLASSES_TO_SKIP = 'classes_to_skip';

    /**
     * @var array<string>
     */
    private $classesToSkip = [
        ObjectCollection::class,
        FormBuilderInterface::class,
        FormBuilderInterface::class,
        OptionsResolver::class,
        FormView::class,
        Throwable::class,
        ArrayObject::class,
        Exception::class,
    ];

    /**
     * @var bool
     */
    private $hasChanged = false;

    /**
     * @var \Rector\Naming\Guard\BreakingVariableRenameGuard
     */
    private $breakingVariableRenameGuard;

    /**
     * @var \Rector\Naming\Naming\ExpectedNameResolver
     */
    private $expectedNameResolver;

    /**
     * @var \Rector\Naming\ExpectedNameResolver\MatchParamTypeExpectedNameResolver
     */
    private $matchParamTypeExpectedNameResolver;

    /**
     * @var \Rector\Naming\ValueObjectFactory\ParamRenameFactory
     */
    private $paramRenameFactory;

    /**
     * @var \Rector\Naming\ParamRenamer\ParamRenamer
     */
    private $paramRenamer;

    /**
     * @param \Rector\Naming\Guard\BreakingVariableRenameGuard $breakingVariableRenameGuard
     * @param \Rector\Naming\Naming\ExpectedNameResolver $expectedNameResolver
     * @param \Rector\Naming\ExpectedNameResolver\MatchParamTypeExpectedNameResolver $matchParamTypeExpectedNameResolver
     * @param \Rector\Naming\ValueObjectFactory\ParamRenameFactory $paramRenameFactory
     * @param \Rector\Naming\ParamRenamer\ParamRenamer $paramRenamer
     */
    public function __construct(
        BreakingVariableRenameGuard $breakingVariableRenameGuard,
        ExpectedNameResolver $expectedNameResolver,
        MatchParamTypeExpectedNameResolver $matchParamTypeExpectedNameResolver,
        ParamRenameFactory $paramRenameFactory,
        ParamRenamer $paramRenamer
    ) {
        $this->breakingVariableRenameGuard = $breakingVariableRenameGuard;
        $this->expectedNameResolver = $expectedNameResolver;
        $this->matchParamTypeExpectedNameResolver = $matchParamTypeExpectedNameResolver;
        $this->paramRenameFactory = $paramRenameFactory;
        $this->paramRenamer = $paramRenamer;
    }

    /**
     * @param array $configuration
     *
     * @return void
     */
    public function configure(array $configuration): void
    {
        $classesToSkip = $configuration[static::CLASSES_TO_SKIP] ?? $configuration;

        $this->classesToSkip = array_merge($classesToSkip, $this->classesToSkip);
    }

    /**
     * @return \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Rename param to match ClassType',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run(FooBarTransfer $fooBar)
    {
        $foo = $fooBar;
    }
}
CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run(FooBarTransfer $fooBarTransfer)
    {
        $foo = $fooBarTransfer;
    }
}
CODE_SAMPLE,
                ),
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run(SpyFooBar $fooBar)
    {
        $foo = $fooBar;
    }
}
CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run(SpyFooBar $fooBarEntity)
    {
        $foo = $fooBarEntity;
    }
}
CODE_SAMPLE,
                ),
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run(SpyFooBarQuery $fooBar)
    {
        $foo = $fooBar;
    }
}
CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run(SpyFooBarQuery $fooBarQuery)
    {
        $foo = $fooBarQuery;
    }
}
CODE_SAMPLE,
                ),
            ],
        );
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
        $this->hasChanged = \false;

        foreach ($node->params as $param) {
            if ($param->type === null || $param->type instanceof NullableType) {
                continue;
            }

            if (in_array((string)$param->type, $this->classesToSkip)) {
                continue;
            }

            $expectedName = $this->expectedNameResolver->resolveForParamIfNotYet($param);

            if ($expectedName === null) {
                continue;
            }

            $expectedName = $this->getExpectedName($expectedName);

            if ($this->shouldSkipParam($param, $expectedName, $node)) {
                continue;
            }

            $expectedName = $this->matchParamTypeExpectedNameResolver->resolve($param);

            if ($expectedName === null) {
                continue;
            }

            $expectedName = $this->getExpectedName($expectedName);

            $paramRename = $this->paramRenameFactory->createFromResolvedExpectedName($param, $expectedName);

            if (!$paramRename instanceof ParamRename) {
                continue;
            }

            $this->paramRenamer->rename($paramRename);
            $this->hasChanged = \true;
        }

        if (!$this->hasChanged) {
            return null;
        }

        return $node;
    }

    /**
     * @param string $expectedName
     *
     * @return string
     */
    protected function getExpectedName(string $expectedName): string
    {
        // Propel Query
        if (preg_match('/^spy[a-zA-Z]+Query/', $expectedName)) {
            return lcfirst(ltrim($expectedName, 'spy'));
        }

        // Propel Entity
        if (preg_match('/^spy[a-zA-Z]+/', $expectedName)) {
            return lcfirst(ltrim($expectedName, 'spy')) . 'Entity';
        }

        return $expectedName;
    }

    /**
     * @param \PhpParser\Node\Param $param
     * @param string $expectedName
     * @param \PhpParser\Node\Stmt\ClassMethod $classMethod
     *
     * @return bool
     */
    protected function shouldSkipParam(Param $param, string $expectedName, ClassMethod $classMethod): bool
    {
        /** @var string $paramName */
        $paramName = $this->getName($param);

        if ($this->breakingVariableRenameGuard->shouldSkipParam($paramName, $expectedName, $classMethod, $param)) {
            return \true;
        }

        // promoted property
        if (!$this->isName($classMethod, MethodName::CONSTRUCT)) {
            return \false;
        }

        return $param->flags !== 0;
    }
}
