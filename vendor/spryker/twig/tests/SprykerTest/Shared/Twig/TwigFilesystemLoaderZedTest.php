<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Twig;

use Codeception\Test\Unit;
use Spryker\Service\UtilText\UtilTextService;
use Spryker\Shared\Twig\Cache\CacheInterface;
use Spryker\Shared\Twig\Dependency\Service\TwigToUtilTextServiceBridge;
use Spryker\Shared\Twig\TemplateNameExtractor\TemplateNameExtractor;
use Spryker\Shared\Twig\TemplateNameExtractor\TemplateNameExtractorInterface;
use Spryker\Shared\Twig\TwigFilesystemLoader;
use SprykerTest\Shared\Twig\Stub\CacheStub;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Twig
 * @group TwigFilesystemLoaderZedTest
 * Add your own group annotations below this line
 */
class TwigFilesystemLoaderZedTest extends Unit
{
    public const PATH_TO_PROJECT = __DIR__ . '/Fixtures/src/ProjectNamespace/Zed/Bundle/Presentation';
    public const PATH_TO_CORE = __DIR__ . '/Fixtures/vendor/spryker/bundle/src/CoreNamespace/Zed/Bundle/Presentation';
    public const PATH_TO_CORE_NON_SPLIT = __DIR__ . '/Fixtures/vendor/spryker/spryker/Bundles/%s/src/CoreNamespace/Zed/Bundle/Presentation';
    public const PATH_TO_CORE_3RD_PARTY = __DIR__ . '/Fixtures/vendor/spryker/3rd-party/src/CoreNamespace/Zed/Bundle/Presentation';

    public const CONTENT_PROJECT_FILE = 'project zed file' . PHP_EOL;
    public const CONTENT_CORE_FILE = 'core zed file' . PHP_EOL;
    public const CONTENT_CORE_NON_SPLIT_FILE = 'core zed non split file' . PHP_EOL;
    public const CONTENT_CORE_3RD_PARTY_FILE = 'core zed 3rd party file' . PHP_EOL;

    /**
     * @return void
     */
    public function testGetSourceReturnsContentFromProject(): void
    {
        $filesystemLoader = $this->getFilesystemLoader(static::PATH_TO_PROJECT);

        $this->assertSame(static::CONTENT_PROJECT_FILE, $filesystemLoader->getSource('@Bundle/Controller/index.twig'));
    }

    /**
     * @return void
     */
    public function testGetSourceReturnsContentFromCore(): void
    {
        $filesystemLoader = $this->getFilesystemLoader(static::PATH_TO_CORE);

        $this->assertSame(static::CONTENT_CORE_FILE, $filesystemLoader->getSource('@Bundle/Controller/index.twig'));
    }

    /**
     * @return void
     */
    public function testGetSourceReturnsContentFromCoreNonSplit(): void
    {
        $filesystemLoader = $this->getFilesystemLoader(static::PATH_TO_CORE_NON_SPLIT);

        $this->assertSame(static::CONTENT_CORE_NON_SPLIT_FILE, $filesystemLoader->getSource('Bundle/Controller/index.twig'));
    }

    /**
     * @return void
     */
    public function testGetSourceReturnsContentFrom3rdParty(): void
    {
        $filesystemLoader = $this->getFilesystemLoader(static::PATH_TO_CORE_3RD_PARTY);

        $this->assertSame(static::CONTENT_CORE_3RD_PARTY_FILE, $filesystemLoader->getSource('@Bundle/Controller/index.twig'));
    }

    /**
     * @return void
     */
    public function testGetSourceReturnsContentFrom3rdPartyAndConvertsBundleNameToPackageNameInSplit(): void
    {
        $filesystemLoader = $this->getFilesystemLoaderForSplitBundleConverterTest();

        $this->assertSame(static::CONTENT_CORE_FILE, $filesystemLoader->getSource('@Bundle/Controller/index.twig'));
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Twig\TwigFilesystemLoader
     */
    protected function getFilesystemLoaderForSplitBundleConverterTest(): TwigFilesystemLoader
    {
        $mockBuilder = $this->getMockBuilder(TwigFilesystemLoader::class)
            ->setMethods(['isPathInSplit'])
            ->setConstructorArgs([[static::PATH_TO_CORE], $this->getCacheStub(), $this->getTemplateNameExtractor()]);

        $mock = $mockBuilder->getMock();
        $mock->expects($this->once())->method('isPathInSplit')->willReturn(true);

        return $mock;
    }

    /**
     * @return \Spryker\Shared\Twig\Cache\CacheInterface
     */
    protected function getCacheStub(): CacheInterface
    {
        return new CacheStub();
    }

    /**
     * @return \Spryker\Shared\Twig\TemplateNameExtractor\TemplateNameExtractorInterface
     */
    protected function getTemplateNameExtractor(): TemplateNameExtractorInterface
    {
        $twigToUtilTextBridge = new TwigToUtilTextServiceBridge(new UtilTextService());
        $templateNameExtractor = new TemplateNameExtractor($twigToUtilTextBridge);

        return $templateNameExtractor;
    }

    /**
     * @param string $path
     * @param \Spryker\Shared\Twig\Cache\CacheInterface|null $cache
     *
     * @return \Spryker\Shared\Twig\TwigFilesystemLoader
     */
    protected function getFilesystemLoader(string $path, ?CacheInterface $cache = null): TwigFilesystemLoader
    {
        if (!$cache) {
            $cache = $this->getCacheStub();
        }

        return new TwigFilesystemLoader([$path], $cache, $this->getTemplateNameExtractor());
    }
}
