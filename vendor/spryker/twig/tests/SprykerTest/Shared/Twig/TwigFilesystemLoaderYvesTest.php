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
 * @group TwigFilesystemLoaderYvesTest
 * Add your own group annotations below this line
 */
class TwigFilesystemLoaderYvesTest extends Unit
{
    public const PATH_TO_PROJECT = __DIR__ . '/Fixtures/src/ProjectNamespace/Yves/Bundle/Theme/default';
    public const PATH_TO_PROJECT_CUSTOM_THEME = __DIR__ . '/Fixtures/src/ProjectNamespace/Yves/Bundle/Theme/custom';
    public const PATH_TO_CORE = __DIR__ . '/Fixtures/vendor/spryker/bundle/src/CoreNamespace/Yves/Bundle/Theme/default';
    public const PATH_TO_CORE_NON_SPLIT = __DIR__ . '/Fixtures/vendor/spryker/spryker/Bundles/*/src/CoreNamespace/Yves/Bundle/Theme/default';
    public const PATH_TO_CORE_3RD_PARTY = __DIR__ . '/Fixtures/vendor/spryker/3rd-party/src/CoreNamespace/Yves/Bundle/Theme/default';

    public const CONTENT_PROJECT_FILE = 'project yves file' . PHP_EOL;
    public const CONTENT_PROJECT_CUSTOM_THEME_FILE = 'project custom theme yves file' . PHP_EOL;
    public const CONTENT_CORE_FILE = 'core yves file' . PHP_EOL;
    public const CONTENT_CORE_NON_SPLIT_FILE = 'core yves non split file' . PHP_EOL;
    public const CONTENT_CORE_3RD_PARTY_FILE = 'core yves 3rd party file' . PHP_EOL;

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
    public function testGetSourceReturnsContentFromProjectDefaultTheme(): void
    {
        $filesystemLoader = $this->getFilesystemLoader(static::PATH_TO_PROJECT);

        $this->assertSame(static::CONTENT_PROJECT_FILE, $filesystemLoader->getSource('@ProjectNamespace:Bundle:default/Controller/index.twig'));
    }

    /**
     * @return void
     */
    public function testGetSourceReturnsContentFromProjectCustomTheme(): void
    {
        $filesystemLoader = $this->getFilesystemLoader(static::PATH_TO_PROJECT_CUSTOM_THEME);

        $this->assertSame(static::CONTENT_PROJECT_CUSTOM_THEME_FILE, $filesystemLoader->getSource('@ProjectNamespace:Bundle:custom/Controller/index.twig'));
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

        $this->assertSame(static::CONTENT_CORE_NON_SPLIT_FILE, $filesystemLoader->getSource('@Bundle/Controller/index.twig'));
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
