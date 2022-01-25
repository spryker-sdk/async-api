<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Kernel;

use Codeception\Test\Unit;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Kernel
 * @group AbstractBundleDependencyProviderTest
 * Add your own group annotations below this line
 */
class AbstractBundleDependencyProviderTest extends Unit
{
    /**
     * @return void
     */
    public function testCallProvidePersistenceLayerDependenciesMustReturnContainer(): void
    {
        $container = new Container();

        $abstractDependencyProviderMock = $this->getAbstractBundleDependencyProviderMock();
        $expected = $abstractDependencyProviderMock->providePersistenceLayerDependencies($container);

        $this->assertSame($expected, $container);
    }

    /**
     * @return void
     */
    public function testCallProvideCommunicationLayerDependenciesMustReturnContainer(): void
    {
        $container = new Container();

        $abstractDependencyProviderMock = $this->getAbstractBundleDependencyProviderMock();
        $expected = $abstractDependencyProviderMock->provideCommunicationLayerDependencies($container);

        $this->assertSame($expected, $container);
    }

    /**
     * @return void
     */
    public function testCallProvideBusinessLayerDependenciesMustReturnContainer(): void
    {
        $container = new Container();

        $abstractDependencyProviderMock = $this->getAbstractBundleDependencyProviderMock();
        $expected = $abstractDependencyProviderMock->provideBusinessLayerDependencies($container);

        $this->assertSame($expected, $container);
    }

    /**
     * @return \Spryker\Zed\Kernel\AbstractBundleDependencyProvider
     */
    private function getAbstractBundleDependencyProviderMock(): AbstractBundleDependencyProvider
    {
        return $this->getMockForAbstractClass(AbstractBundleDependencyProvider::class);
    }
}
