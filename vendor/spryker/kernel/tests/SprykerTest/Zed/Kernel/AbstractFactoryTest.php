<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Kernel;

use Codeception\Test\Unit;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\AbstractFactory;
use Spryker\Zed\Kernel\ClassResolver\DependencyInjector\DependencyInjectorResolver;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Kernel\Dependency\Injector\DependencyInjectorCollection;
use Spryker\Zed\Kernel\Dependency\Injector\DependencyInjectorInterface;
use Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException;
use SprykerTest\Zed\Kernel\Fixtures\Factory;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Kernel
 * @group AbstractFactoryTest
 * Add your own group annotations below this line
 */
class AbstractFactoryTest extends Unit
{
    /**
     * @var string
     */
    public const CONTAINER_KEY = 'key';

    /**
     * @var string
     */
    public const CONTAINER_VALUE = 'value';

    /**
     * @return void
     */
    public function testSetContainer(): void
    {
        $container = new Container();
        $factory = new Factory();

        $this->assertSame($factory, $factory->setContainer($container));
    }

    /**
     * @return void
     */
    public function testGetProvidedDependencyThrowsException(): void
    {
        $container = new Container();
        $factory = new Factory();

        $this->expectException(ContainerKeyNotFoundException::class);
        $factory->setContainer($container);
        $factory->getProvidedDependency('something');
    }

    /**
     * @return void
     */
    public function testGetProvidedDependency(): void
    {
        $container = new Container();
        $container->set(static::CONTAINER_KEY, static::CONTAINER_VALUE);
        $factory = new Factory();

        $factory->setContainer($container);
        $this->assertSame(static::CONTAINER_VALUE, $factory->getProvidedDependency(static::CONTAINER_KEY));
    }

    /**
     * @return void
     */
    public function testGetProvidedDependencyShouldResolveContainer(): void
    {
        $container = new Container();
        $container->set(static::CONTAINER_KEY, static::CONTAINER_VALUE);

        $factoryMock = $this->getFactoryMock(['createContainerWithProvidedDependencies']);
        $factoryMock->expects($this->once())->method('createContainerWithProvidedDependencies')->willReturn($container);

        $this->assertSame(static::CONTAINER_VALUE, $factoryMock->getProvidedDependency(static::CONTAINER_KEY));
    }

    /**
     * @return void
     */
    public function testGetProvidedDependencyShouldGetInjectedData(): void
    {
        $dependencyInjectorResolver = $this->getDependencyInjectorResolverMock();
        $factoryMock = $this->getFactoryMock(['createDependencyInjectorResolver', 'resolveDependencyProvider']);
        $factoryMock->expects($this->once())->method('createDependencyInjectorResolver')->willReturn($dependencyInjectorResolver);

        $abstractBundleDependencyProviderMock = $this->getMockForAbstractClass(AbstractBundleDependencyProvider::class);
        $factoryMock->expects($this->once())->method('resolveDependencyProvider')->willReturn($abstractBundleDependencyProviderMock);

        $this->assertSame(static::CONTAINER_VALUE, $factoryMock->getProvidedDependency(static::CONTAINER_KEY));
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Kernel\ClassResolver\DependencyInjector\DependencyInjectorResolver
     */
    protected function getDependencyInjectorResolverMock(): DependencyInjectorResolver
    {
        $container = new Container();
        $container->set(static::CONTAINER_KEY, static::CONTAINER_VALUE);

        $dependencyInjectorMock = $this->getMockBuilder(DependencyInjectorInterface::class)->getMock();
        $dependencyInjectorMock->expects($this->once())->method('injectBusinessLayerDependencies')->willReturn($container);
        $dependencyInjectorMock->expects($this->once())->method('injectCommunicationLayerDependencies')->willReturn($container);
        $dependencyInjectorMock->expects($this->once())->method('injectPersistenceLayerDependencies')->willReturn($container);

        $dependencyInjectorCollectionMock = $this->getMockBuilder(DependencyInjectorCollection::class)->setMethods(['getDependencyInjector'])->getMock();
        $dependencyInjectorCollectionMock->method('getDependencyInjector')->willReturn([$dependencyInjectorMock]);

        $dependencyInjectorResolverMock = $this->getMockBuilder(DependencyInjectorResolver::class)->setMethods(['resolve'])->getMock();
        $dependencyInjectorResolverMock->expects($this->once())->method('resolve')->willReturn($dependencyInjectorCollectionMock);

        return $dependencyInjectorResolverMock;
    }

    /**
     * @param array $methods
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Kernel\AbstractFactory
     */
    protected function getFactoryMock(array $methods): AbstractFactory
    {
        $factoryMock = $this->getMockBuilder(Factory::class)->setMethods($methods)->getMock();

        return $factoryMock;
    }
}
