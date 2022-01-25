<?php declare(strict_types = 1);

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Architector\Codeception\PresentationToControllerTest;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassLike;
use Rector\Core\Rector\AbstractRector;
use Rector\FileSystemRector\ValueObject\AddedFileWithNodes;
use SprykerSdk\Architector\Codeception\RectorHelper\TestSuiteHelper;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class PresentationToControllerTestFileMoveRector extends AbstractRector
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
        return [ClassLike::class];
    }

    /**
     * @param \PhpParser\Node\Stmt\ClassLike $node
     *
     * @return \PhpParser\Node|null
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->testSuiteHelper->isSuite('Presentation', $node)) {
            return null;
        }

        $newStatements = $this->file->getNewStmts();

        $smartFileInfo = $this->file->getSmartFileInfo();
        $newFileLocation = str_replace('/Presentation/', '/Controller/', $smartFileInfo->getPathname());
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
            'Moves Presentation test files to Controller directory',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
// file: tests/Presentation/SomeTest.php
namespace Foo\Presentation;
class SomeTest
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
