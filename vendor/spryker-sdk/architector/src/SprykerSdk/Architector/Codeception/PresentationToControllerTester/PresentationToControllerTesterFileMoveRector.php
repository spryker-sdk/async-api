<?php declare(strict_types = 1);

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Architector\Codeception\PresentationToControllerTester;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Rector\Core\Rector\AbstractRector;
use Rector\FileSystemRector\ValueObject\AddedFileWithNodes;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class PresentationToControllerTesterFileMoveRector extends AbstractRector
{
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
        $smartFileInfo = $this->file->getSmartFileInfo();

        if (strpos($smartFileInfo->getFilename(), 'PresentationTester.php') === false) {
            return null;
        }

        $newStatements = $this->file->getNewStmts();

        $newFileLocation = str_replace('PresentationTester.php', 'ControllerTester.php', $smartFileInfo->getPathname());
        $addedFileWithContent = new AddedFileWithNodes($newFileLocation, $newStatements);
        $this->removedAndAddedFilesCollector->removeFile($smartFileInfo);
        $this->removedAndAddedFilesCollector->addAddedFile($addedFileWithContent);

        return null;
    }

    /**
     * @return \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Moves Presentation test to Controller test suite namespace',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
// file: tests/Presentation/SomeTest.php
namespace Foo\Presentation;
class SomePresentationTest
{
}
CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
// file: tests/Controller/SomeTest.php
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
