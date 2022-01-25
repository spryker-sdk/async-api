<?php declare(strict_types = 1);

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Architector\TriggerError;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class TriggerErrorMessagesWithSprykerPrefixRector extends AbstractRector
{
    private string $sprykerPrefix = 'Spryker: ';

    /**
     * @return array<class-string<\PhpParser\Node>>
     */
    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    /**
     * @param \PhpParser\Node\Expr\FuncCall $node
     *
     * @return \PhpParser\Node|null
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->getName($node) === 'trigger_error') {
            /** @var \PhpParser\Node\Arg $firstArgument */
            $firstArgument = $node->args[0];
            $messageArgument = $firstArgument->value;

            if ($messageArgument instanceof Concat) {
                return $this->refactorConcatinatedString($messageArgument, $node);
            }

            if ($messageArgument instanceof String_) {
                return $this->refactorString($messageArgument, $node);
            }

            if ($messageArgument instanceof Variable) {
                $this->refactorVariable($messageArgument);

                return null;
            }

            if ($messageArgument instanceof FuncCall) {
                return $this->refactorSprintf($messageArgument, $node);
            }
        }

        return null;
    }

    /**
     * @param \PhpParser\Node\Expr\BinaryOp\Concat $messageArgument
     * @param \PhpParser\Node\Expr\FuncCall $node
     *
     * @return \PhpParser\Node\Expr\FuncCall|null
     */
    private function refactorConcatinatedString(Concat $messageArgument, FuncCall $node): ?FuncCall
    {
        /** @var \PhpParser\Node\Scalar\String_|null $mostLeftStringNode */
        $mostLeftStringNode = $this->betterNodeFinder->findFirstInstanceOf($messageArgument->left, String_::class);

        if (!$mostLeftStringNode) {
            return null;
        }

        $currentMessage = $mostLeftStringNode->value;
        $newMessage = $this->formatMessage($currentMessage);

        if ($newMessage) {
            $mostLeftStringNode->value = $newMessage;

            return $node;
        }

        return null;
    }

    /**
     * @param \PhpParser\Node\Scalar\String_ $messageArgument
     * @param \PhpParser\Node\Expr\FuncCall $node
     *
     * @return \PhpParser\Node\Expr\FuncCall|null
     */
    private function refactorString(String_ $messageArgument, FuncCall $node): ?FuncCall
    {
        $currentMessage = $messageArgument->value;

        $newMessage = $this->formatMessage($currentMessage);

        if ($newMessage) {
            $messageArgument->value = $newMessage;

            return $node;
        }

        return null;
    }

    /**
     * @param \PhpParser\Node\Expr\Variable $messageArgument
     *
     * @return void
     */
    private function refactorVariable(Variable $messageArgument): void
    {
        $previousAssign = $this->betterNodeFinder->findPreviousAssignToExpr($messageArgument);

        if (!$previousAssign) {
            return;
        }

        // Find the string node we are interested in. This can be:
        // - $message = 'Foo';
        // - $message = 'Foo' . 'Bar';
        // - $message = 'Foo' . 'Bar' . 'Baz';
        /** @var \PhpParser\Node\Scalar\String_ $mostLeftStringNode */
        $mostLeftStringNode = $this->betterNodeFinder->findFirstInstanceOf($previousAssign->expr, String_::class);

        $currentMessage = $mostLeftStringNode->value;
        $newMessage = $this->formatMessage($currentMessage);

        if ($newMessage) {
            $mostLeftStringNode->value = $newMessage;
        }
    }

    /**
     * @param \PhpParser\Node\Expr\FuncCall $messageArgument
     * @param \PhpParser\Node\Expr\FuncCall $node
     *
     * @return \PhpParser\Node\Expr\FuncCall|null
     */
    private function refactorSprintf(FuncCall $messageArgument, FuncCall $node): ?FuncCall
    {
        if ($this->getName($messageArgument) !== 'sprintf') {
            return null;
        }

        /** @var \PhpParser\Node\Arg $firstArgument */
        $firstArgument = $messageArgument->args[0];
        $stringArgument = $firstArgument->value;

        if (!($stringArgument instanceof String_)) {
            return null;
        }

        $currentMessage = $stringArgument->value;
        $newMessage = $this->formatMessage($currentMessage);

        if ($newMessage) {
            $stringArgument->value = $newMessage;

            return $node;
        }

        return null;
    }

    /**
     * Return a message prefixed when prefix doesn't exists otherwise null to indicate nothing was changed.
     *
     * @param string $message
     *
     * @return string|null
     */
    private function formatMessage(string $message): ?string
    {
        if (substr($message, 0, strlen($this->sprykerPrefix)) !== $this->sprykerPrefix) {
            return $this->sprykerPrefix . $message;
        }

        return null;
    }

    /**
     * @return \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Refactors trigger_error calls to ensure the passed message contains "Spryker: " as prefix.',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
trigger_error('My message', E_USER_DEPRECATED);
CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
trigger_error('Spryker: My message', E_USER_DEPRECATED);
CODE_SAMPLE,
                ),
                new CodeSample(
                    <<<'CODE_SAMPLE'
$message = 'Foo';
trigger_error($message, E_USER_DEPRECATED);
CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
$message = 'Spryker: Foo';
trigger_error($message, E_USER_DEPRECATED);
CODE_SAMPLE,
                ),
                new CodeSample(
                    <<<'CODE_SAMPLE'
$message = 'Foo' . 'Bar';
trigger_error($message, E_USER_DEPRECATED);
CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
$message = 'Spryker: Foo' . 'Bar';
trigger_error($message, E_USER_DEPRECATED);
CODE_SAMPLE,
                ),
                new CodeSample(
                    <<<'CODE_SAMPLE'
$message = 'Foo' . 'Bar' . 'Baz';
trigger_error($message, E_USER_DEPRECATED);
CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
$message = 'Spryker: Foo' . 'Bar' . 'Baz';
trigger_error($message, E_USER_DEPRECATED);
CODE_SAMPLE,
                ),
                new CodeSample(
                    <<<'CODE_SAMPLE'
$message = sprintf('Foo %s', $something);
trigger_error($message, E_USER_DEPRECATED);
CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
$message = sprintf('Spryker: Foo %s', $something);
trigger_error($message, E_USER_DEPRECATED);
CODE_SAMPLE,
                ),
            ],
        );
    }
}
